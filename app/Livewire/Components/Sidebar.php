<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'route' => 'admin.dashboard',
                'icon' => 'data-feather=home',
                'active' => request()->routeIs('admin.dashboard') ? 'active' : '',
            ],
            [
                'name' => 'Data User',
                'route' => 'admin.user',
                'icon' => 'data-feather=users',
                'active' => request()->routeIs('admin.user') ? 'active' : '',
            ],
            [
                'name' => 'Data Alumni Magang',
                'route' => 'admin.alumni-magang',
                'icon' => 'data-feather=users',
                'active' => request()->routeIs('admin.alumni-magang') ? 'active' : '',
            ],
            [
                'name' => 'Data Peserta Magang',
                'route' => 'admin.peserta-magang',
                'icon' => 'data-feather=users',
                'active' => request()->routeIs('admin.peserta-magang') ? 'active' : '',
            ],
            [
                'name' => 'Data Pengajuan Magang',
                'route' => 'admin.pengajuan',
                'icon' => 'data-feather=users',
                'active' => request()->routeIs('admin.pengajuan') ? 'active' : '',
            ],
        ];
        return view('livewire.components.sidebar',[
            'menus' => $menus
        ]);
    }
}
