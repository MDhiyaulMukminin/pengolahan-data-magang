<?php

namespace App\Livewire\Public\Register;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert;
    
    public $email, $password, $password_confirmation;

    // Fix: Listener harus berupa array
    protected $listeners = ['redirectAfterRegister'];

    public function render()
    {
        return view('livewire.public.register.index')->layout('components.layouts.register.auth');
    }

    public function saveDaftar()
    {
        $validasi = $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ], [
            'email.required' => 'Email Harus Diisi.',
            'email.email' => 'Email Tidak Valid.',
            'email.unique' => 'Email Sudah Terdaftar.',
            'password.required' => 'Password Harus Diisi.',
            'password.min' => 'Password Minimal 6 Karakter.',
            'password_confirmation.required' => 'Konfirmasi Password Harus Diisi.',
            'password_confirmation.same' => 'Konfirmasi Password Tidak Sama Dengan Password.'
        ]);

        DB::beginTransaction();
        try {
            $user = new User();
            $user->email = $this->email;
            $user->password = Hash::make($this->password);
            $user->role_id = 2; // Default ke role user
            $user->status_akun = 'belum lengkapi profile'; // Set status awal
            $user->save();
            
            DB::commit();
            
            // Reset form
            $this->reset(['email', 'password', 'password_confirmation']);
            
            $this->alert('success', 'Registrasi Berhasil!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => false,
                'text' => 'Anda akan diarahkan ke halaman login.',
                'showCancelButton' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'redirectAfterRegister'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', 'Registrasi Gagal!', [
                'position' => 'center',
                'timer' => 5000,
                'toast' => false,
                'text' => $e->getMessage()
            ]);
        }
    }
    
    public function redirectAfterRegister()
    {
        // Method 1: Menggunakan redirect dengan session flash
        session()->flash('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        return $this->redirect(route('auth.login'));
    }
}