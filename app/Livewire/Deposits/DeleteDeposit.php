<?php

namespace App\Livewire\Deposits;

use App\Models\Deposit;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class DeleteDeposit extends Component
{
    public Deposit $deposit;

    /**
     * Render the view.
     */
    public function render(): View
    {
        return view('livewire.deposits.delete-deposit');
    }

    /**
     * Set the specified model instance for the component.
     */
    #[On('deposit-delete')]
    public function setValue(Deposit $deposit): void
    {
        $this->deposit = $deposit;
    }

    /**
     * Remove the specified resource from storage and handle the related events.
     */
    public function destroy(): void
    {
        $this->deposit->delete();

        $this->dispatch('close-modal');
        $this->dispatch('success', message: 'Data berhasil dihapus!');
        $this->dispatch('deposit-deleted')->to(DepositCurrentWeekTable::class);
    }
}
