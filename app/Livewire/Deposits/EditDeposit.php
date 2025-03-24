<?php

namespace App\Livewire\Deposits;

use App\Livewire\Forms\UpdateDepositForm;
use App\Models\Deposit;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class EditDeposit extends Component
{
    public UpdateDepositForm $form;

    public Collection $students;

    /**
     * Render the view.
     */
    public function render(): View
    {
        return view('livewire.deposits.edit-deposit', [
            'students' => $this->students,
        ]);
    }

    /**
     * Set the specified model instance for the component.
     */
    #[On('deposit-edit')]
    public function setValue(Deposit $deposit): void
    {
        $this->form->deposit = $deposit;
        $this->form->fill($deposit);
    }

    /**
     * Update the form data and handle the related events.
     */
    public function edit(): void
    {
        $this->form->update();

        $this->dispatch('close-modal');
        $this->dispatch('success', message: 'Data berhasil diubah!');
        $this->dispatch('deposit-updated')->to(DepositCurrentWeekTable::class);
    }
}
