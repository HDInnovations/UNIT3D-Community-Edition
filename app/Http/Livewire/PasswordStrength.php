<?php

namespace App\Http\Livewire;

use Livewire\Component;
use ZxcvbnPhp\Zxcvbn;

class PasswordStrength extends Component
{
    public string $password = '';

    public string $passwordStrength = 'Weak';

    public int $strengthScore = 0;

    public array $strengthLevels = [
        1 => 'Weak',
        2 => 'Fair',
        3 => 'Good',
        4 => 'Strong',
    ];

    final public function updatedPassword($password): void
    {
        $this->strengthScore = (new Zxcvbn())->passwordStrength($password)['score'];
    }

    final public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.password-strength');
    }
}
