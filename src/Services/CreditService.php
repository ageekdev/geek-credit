<?php

namespace Ageekdev\GeekCredit\Services;

use Ageekdev\GeekCredit\Enums\CreditTransactionType;
use Ageekdev\GeekCredit\Models\Credit;
use Ageekdev\GeekCredit\Models\CreditTransaction;
use Ageekdev\GeekCredit\Models\CreditTransactionDetail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PDOException;

class CreditService
{
    public function __construct(
        private readonly Credit $creditModel,
        private readonly CreditTransaction $creditTransactionModel,
        private readonly CreditTransactionDetail $creditTransactionDetailModel,
    ) {
    }

    public function getTotalCreditByHolder(Model $holder)
    {
        return $this->creditModel
            ->isHolder($holder)
            ->notExpired()
            ->hasRemainingBalance()
            ->sum('remaining_balance');
    }

    public function addCreditToHolder(
        Model $holder,
        float $amount,
        string $name,
        ?Carbon $expiresAt = null,
        ?string $description = null,
        ?array $meta = null,
    ): void {
        DB::beginTransaction();

        try {
            $creditTransaction = $this->creditTransactionModel->create([
                'holder_type' => $holder->getMorphClass(),
                'holder_id' => $holder->getKey(),
                'amount' => $amount,
                'type' => CreditTransactionType::In,
                'name' => $name,
                'description' => $description,
                'meta' => $meta,
            ]);

            if (! $expiresAt) {
                $credit = $this->addNonExpiringCredit(
                    $holder,
                    $amount,
                );
            } else {
                $credit = $this->addExpiringCredit(
                    $holder,
                    $amount,
                    $expiresAt,
                );
            }

            $creditTransaction->details()->create([
                'credit_id' => $credit->getKey(),
                'amount' => $amount,
            ]);

            DB::commit();
        } catch (PDOException $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function addNonExpiringCredit(
        Model $holder,
        float $amount,
    ): Credit {
        $credit = $this->creditModel->query()
            ->where('can_expire', false)
            ->isHolder($holder)
            ->lockForUpdate()
            ->first();

        if (! $credit) {
            $credit = $this->creditModel->newInstance([
                'holder_type' => $holder->getMorphClass(),
                'holder_id' => $holder->getKey(),
                'initial_balance' => 0,
                'remaining_balance' => 0,
                'can_expire' => false,
            ]);
        }

        $credit->remaining_balance += $amount;
        $credit->save();

        return $credit;
    }

    public function addExpiringCredit(
        Model $holder,
        float $amount,
        Carbon $expiresAt,
    ): Credit {
        return $this->creditModel->create([
            'holder_type' => $holder->getMorphClass(),
            'holder_id' => $holder->getKey(),
            'initial_balance' => $amount,
            'remaining_balance' => $amount,
            'expires_at' => $expiresAt,
            'can_expire' => true,
        ]);
    }

    public function useCredit(
        Model $holder,
        float $amount,
        string $name,
        ?string $description = null,
        ?array $meta = null,
    ) {

        if (! $this->checkHolderCredit($holder, $amount)) {
            throw new \Exception('Insufficient credit');
        }

        DB::beginTransaction();
        try {

            $creditTransaction = $this->creditTransactionModel->create([
                'holder_type' => $holder->getMorphClass(),
                'holder_id' => $holder->getKey(),
                'amount' => $amount,
                'type' => CreditTransactionType::Out,
                'name' => $name,
                'description' => $description,
                'meta' => $meta,
            ]);

            $credits = $this->getRemainingCreditByHolderForUpdate($holder);

            $remainingAmount = $amount;

            $creditUpdates = [];
            $details = [];

            foreach ($credits as $credit) {
                $remainingAmount -= $credit->remaining_balance;

                $creditUpdates[] = [
                    'id' => $credit->getKey(),
                    'remaining_balance' => $remainingAmount > 0
                        ? 0
                        : abs($remainingAmount),
                    'holder_type' => $credit->holder_type,
                    'holder_id' => $credit->holder_id,
                    'can_expire' => $credit->can_expire,
                ];

                $details[] = [
                    'credit_id' => $credit->getKey(),
                    'amount' => $remainingAmount > 0
                        ? $credit->remaining_balance
                        : abs($remainingAmount),
                    'credit_transaction_id' => $creditTransaction->getKey(),
                ];

                if ($remainingAmount <= 0) {
                    break;
                }
            }

            $this->creditModel
                ->query()
                ->upsert(
                    $creditUpdates,
                    ['id'],
                );

            if (count($details) > 0) {
                $this->creditTransactionDetailModel->insert($details);
            }

            DB::commit();
        } catch (PDOException $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function getRemainingCreditByHolderForUpdate(
        Model $holder,
    ): Collection {
        return $this->creditModel
            ->query()
            ->isHolder($holder)
            ->notExpired()
            ->hasRemainingBalance()
            ->orderBy('can_expire', 'desc')
            ->orderBy('expires_at', 'asc')
            ->lockForUpdate()
            ->get([
                'id',
                'remaining_balance',
                'holder_type',
                'holder_id',
                'can_expire',
            ]);
    }

    public function getExpiredCredits(): Collection
    {
        return $this->creditModel
            ->query()
            ->where('expires_at', '<', now())
            ->get();
    }

    public function checkHolderCredit(
        Model $holder,
        float $amount,
    ): bool {
        return $this->getTotalCreditByHolder($holder) >= $amount;
    }
}
