<?php

namespace App\Livewire\Public\Login;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert;

    public $email, $password;

    public function render()
    {
        return view('livewire.public.login.index')->layout('components.layouts.login.auth');
    }

    public function login()
    {
        $validasi = $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email Harus Diisi.',
            'email.email' => 'Email Tidak Valid.',
            'password.required' => 'Password Harus Diisi.'
        ]);

        DB::beginTransaction();
        try {
            $user = User::where('email', $this->email)->first();
            if ($user) {
                if (Hash::check($this->password, $user->password)) {
                    // Validasi status akun
                    if ($user->status_akun === 'nonaktif') {
                        $this->alert('error', 'Akun Anda sedang tidak aktif. Silakan hubungi administrator.', [
                            'position' =>  'center',
                            'timer' =>  3000,
                            'toast' =>  false,
                            'text' =>  null,
                            'showCancelButton' =>  false,
                            'showConfirmButton' =>  true
                        ]);
                        return;
                    }

                    // Jika status akun "aktif" atau "belum lengkapi profile", bisa login
                    if ($user->status_akun === 'aktif' || $user->status_akun === 'belum lengkapi profile') {
                        Auth::login($user);
                        DB::commit();

                        // Redirect berdasarkan role user
                        $userRole = $user->role->nama; // Mengakses nama role dari relasi

                        if ($userRole === 'admin') {
                            return redirect()->route('admin.dashboard');
                        } else {
                            return redirect()->route('user.dashboard');
                        }
                    } else {
                        $this->alert('error', 'Status akun Anda tidak valid untuk login.', [
                            'position' =>  'center',
                            'timer' =>  3000,
                            'toast' =>  false,
                            'text' =>  null,
                            'showCancelButton' =>  false,
                            'showConfirmButton' =>  true
                        ]);
                    }
                } else {
                    $this->alert('error', 'Password Tidak Valid.', [
                        'position' =>  'center',
                        'timer' =>  3000,
                        'toast' =>  false,
                        'text' =>  null,
                        'showCancelButton' =>  false,
                        'showConfirmButton' =>  true
                    ]);
                }
            } else {
                $this->alert('error', 'Email Tidak Ditemukan.', [
                    'position' =>  'center',
                    'timer' =>  3000,
                    'toast' =>  false,
                    'text' =>  null,
                    'showCancelButton' =>  false,
                    'showConfirmButton' =>  true
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', $e->getMessage());
        }
    }
}