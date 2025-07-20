<?php

namespace App\Livewire\Admin\PesertaMagang;

use App\Models\PesertaMagang;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Properties pencarian
    public $search = '';

    // Modal kontrol
    public $isOpen = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    // Form fields
    public $peserta_magang_id;
    public $pengajuan_id;
    public $status;

    // Validasi
    protected $rules = [
        'pengajuan_id' => 'required|exists:pengajuans,id',
        'status' => 'required|in:aktif,selesai',
    ];

    // Query string
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        $pesertaMagangs = PesertaMagang::with('pengajuan.user.sekolah')
            ->whereHas('pengajuan.user', function ($query) use ($searchTerm) {
                $query->where('nama', 'like', $searchTerm)
                    ->orWhere('jurusan', 'like', $searchTerm)
                    ->orWhereHas('sekolah', function ($q) use ($searchTerm) {
                        $q->where('nama', 'like', $searchTerm);
                    });
            })
            ->orWhere('status', 'like', $this->search)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.peserta-magang.index', [
            'pesertaMagangs' => $pesertaMagangs
        ]);
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->resetInputFields();

        $peserta = PesertaMagang::findOrFail($id);
        $this->peserta_magang_id = $id;
        $this->pengajuan_id = $peserta->pengajuan_id;
        $this->status = $peserta->status;

        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate();

        PesertaMagang::updateOrCreate(['id' => $this->peserta_magang_id], [
            'pengajuan_id' => $this->pengajuan_id,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Data peserta magang berhasil diperbarui.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteConfirmed()
    {
        $this->delete($this->deleteId);
        $this->closeDeleteModal();
    }

    public function delete($id)
    {
        PesertaMagang::find($id)?->delete();
        session()->flash('message', 'Data peserta magang berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function resetInputFields()
    {
        $this->peserta_magang_id = null;
        $this->pengajuan_id = null;
        $this->status = '';
    }

    public function dismissAlert()
    {
        session()->forget('message');
    }
}
