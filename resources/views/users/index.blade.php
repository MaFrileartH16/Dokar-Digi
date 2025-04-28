@extends('layouts.dashboard')

@section('title', 'Pengguna')

@section('dashboard-header')
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">Daftar Pengguna</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
               data-bs-target="#modal-create-user">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5l0 14"/>
                <path d="M5 12l14 0"/>
              </svg>
              Buat pengguna baru
            </a>
            <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal"
               data-bs-target="#modal-create-user" aria-label="Buat pengguna baru">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5l0 14"/>
                <path d="M5 12l14 0"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('dashboard-content')
  {{-- Flash Message Notifications --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
      <div class="d-flex">
        <div>
          <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24"
               stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M5 12l5 5l10 -10"></path>
          </svg>
        </div>
        <div>{{ session('success') }}</div>
      </div>
      <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
      <div class="d-flex">
        <div>
          <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24"
               stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M12 9v2m0 4v.01"></path>
            <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"></path>
          </svg>
        </div>
        <div>{{ session('error') }}</div>
      </div>
      <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
  @endif

  <div class="card">
    <div class="card-body border-bottom py-3">
      <div class="d-flex">
        <div class="text-secondary">
          Tampilkan
          <div class="mx-2 d-inline-block">
            <select id="perPage" class="form-select form-select-sm" onchange="changePerPage(this.value)">
              <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
              <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
              <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
              <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
          </div>
          entri
        </div>
        <div class="ms-auto text-secondary">
          Cari:
          <div class="ms-2 d-inline-block">
            <form action="{{ route('users.index') }}" method="GET" id="searchForm">
              <input type="hidden" name="sort" value="{{ request('sort') }}">
              <input type="hidden" name="direction" value="{{ request('direction') }}">
              <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
              <input aria-label="Cari pengguna" class="form-control form-control-sm"
                     type="text" name="search" value="{{ request('search') }}"
                     id="searchInput" placeholder="Nama, email, atau jabatan">
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table card-table table-vcenter text-nowrap datatable">
        <thead>
        <tr>
          <th>Foto</th>
          <th>
            <a href="{{ route('users.index', [
                'sort' => 'name',
                'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc',
                'search' => request('search'),
                'per_page' => request('per_page', 10)
            ]) }}" class="table-sort {{ request('sort') == 'name' ? 'table-sort-'.request('direction') : '' }}">
              Nama Lengkap
              @if(request('sort') == 'name')
                <i class="icon ti ti-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
              @endif
            </a>
          </th>
          <th>
            <a href="{{ route('users.index', [
                'sort' => 'email',
                'direction' => request('sort') == 'email' && request('direction') == 'asc' ? 'desc' : 'asc',
                'search' => request('search'),
                'per_page' => request('per_page', 10)
            ]) }}" class="table-sort {{ request('sort') == 'email' ? 'table-sort-'.request('direction') : '' }}">
              Alamat Surel
              @if(request('sort') == 'email')
                <i class="icon ti ti-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
              @endif
            </a>
          </th>
          <th>
            <a href="{{ route('users.index', [
                'sort' => 'role',
                'direction' => request('sort') == 'role' && request('direction') == 'asc' ? 'desc' : 'asc',
                'search' => request('search'),
                'per_page' => request('per_page', 10)
            ]) }}" class="table-sort {{ request('sort') == 'role' ? 'table-sort-'.request('direction') : '' }}">
              Jabatan
              @if(request('sort') == 'role')
                <i class="icon ti ti-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
              @endif
            </a>
          </th>
          <th class="text-end">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($users as $user)
          <tr>
            <td>
              @if ($user->profile_photo)
                <span class="avatar avatar-sm" style="background-image: url('{{ $user->profile_photo }}')"></span>
              @else
                <span class="avatar avatar-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                      <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/>
                      <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                    </svg>
                  </span>
              @endif
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->getRoleNames()->first() }}</td>
            <td class="text-end">
                <span class="dropdown">
                  <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                          data-bs-toggle="dropdown">
                    Aksi
                  </button>
                  <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item edit-user-btn" href="#"
                       data-bs-toggle="modal"
                       data-bs-target="#modal-edit-user"
                       data-id="{{ $user->id }}"
                       data-name="{{ $user->name }}"
                       data-email="{{ $user->email }}"
                       data-role="{{ $user->getRoleNames()->first() }}">
                      Ubah
                    </a>
                    <a class="dropdown-item text-danger" href="#"
                       onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) document.getElementById('delete-user-{{ $user->id }}').submit();">
                      Hapus
                    </a>
                    <form id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}"
                          method="POST" style="display: none;">
                      @csrf
                      @method('DELETE')
                    </form>
                  </div>
                </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center py-4">
              <div class="empty">
                <div class="empty-img">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="40"
                       height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                       stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                  </svg>
                </div>
                <p class="empty-title">Tidak ada data pengguna ditemukan</p>
                @if(request('search'))
                  <p class="empty-subtitle text-secondary">
                    Tidak ada hasil yang cocok dengan pencarian "{{ request('search') }}"
                  </p>
                  <div class="empty-action">
                    <a href="{{ route('users.index') }}" class="btn btn-primary">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                           stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                           stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1"/>
                      </svg>
                      Hapus Filter
                    </a>
                  </div>
                @endif
              </div>
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
        {{-- Informasi Menampilkan --}}
        <div class="mb-3 mb-md-0 text-center text-md-start">
          <p class="m-0 text-secondary">
            @if($users->total() > 0)
              Menampilkan
              <span class="fw-bold">{{ $users->firstItem() }}</span>
              sampai
              <span class="fw-bold">{{ $users->lastItem() }}</span>
              dari
              <span class="fw-bold">{{ $users->total() }}</span>
              entri
            @else
              Menampilkan 0 entri
            @endif
          </p>
        </div>

        {{-- Navigasi Pagination --}}
        @if($users->hasPages())
          <div class="w-100 w-md-auto">
            <ul class="pagination justify-content-center justify-content-md-end mb-0">
              {{-- First Page Link --}}
              @if ($users->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                  <span class="page-link">&lt;&lt;</span>
                </li>
              @else
                <li class="page-item">
                  <a class="page-link"
                     href="{{ $users->url(1) }}&sort={{ request('sort') }}&direction={{ request('direction') }}&search={{ request('search') }}&per_page={{ request('per_page', 10) }}"
                     title="Ke halaman pertama">&lt;&lt;</a>
                </li>
              @endif

              {{-- Previous Page Link --}}
              @if ($users->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                  <span class="page-link">&lt;</span>
                </li>
              @else
                <li class="page-item">
                  <a class="page-link"
                     href="{{ $users->previousPageUrl() }}&sort={{ request('sort') }}&direction={{ request('direction') }}&search={{ request('search') }}&per_page={{ request('per_page', 10) }}"
                     rel="prev" title="Sebelumnya">&lt;</a>
                </li>
              @endif

              {{-- Current Page (Mobile) --}}
              <li class="page-item active d-sm-none" aria-current="page">
                <span class="page-link">{{ $users->currentPage() }}</span>
              </li>

              {{-- Page Numbers (Desktop) --}}
              @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                @if ($page == 1 || $page == $users->lastPage() || ($page >= $users->currentPage() - 1 && $page <= $users->currentPage() + 1))
                  @if ($page == $users->currentPage())
                    <li class="page-item active d-none d-sm-block" aria-current="page">
                      <span class="page-link">{{ $page }}</span>
                    </li>
                  @else
                    <li class="page-item d-none d-sm-block">
                      <a class="page-link"
                         href="{{ $url }}&sort={{ request('sort') }}&direction={{ request('direction') }}&search={{ request('search') }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a>
                    </li>
                  @endif
                @elseif (($page == $users->currentPage() - 2 || $page == $users->currentPage() + 2) && $page != 1 && $page != $users->lastPage())
                  <li class="page-item disabled d-none d-sm-block">
                    <span class="page-link">...</span>
                  </li>
                @endif
              @endforeach

              {{-- Next Page Link --}}
              @if ($users->hasMorePages())
                <li class="page-item">
                  <a class="page-link"
                     href="{{ $users->nextPageUrl() }}&sort={{ request('sort') }}&direction={{ request('direction') }}&search={{ request('search') }}&per_page={{ request('per_page', 10) }}"
                     rel="next" title="Selanjutnya">&gt;</a>
                </li>
              @else
                <li class="page-item disabled" aria-disabled="true">
                  <span class="page-link">&gt;</span>
                </li>
              @endif

              {{-- Last Page Link --}}
              @if ($users->currentPage() == $users->lastPage())
                <li class="page-item disabled" aria-disabled="true">
                  <span class="page-link">&gt;&gt;</span>
                </li>
              @else
                <li class="page-item">
                  <a class="page-link"
                     href="{{ $users->url($users->lastPage()) }}&sort={{ request('sort') }}&direction={{ request('direction') }}&search={{ request('search') }}&per_page={{ request('per_page', 10) }}"
                     title="Ke halaman terakhir">&gt;&gt;</a>
                </li>
              @endif
            </ul>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Modal for creating new user --}}
  <div class="modal modal-blur fade" id="modal-create-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Buat Pengguna Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label required">Nama Lengkap</label>
              <input type="text" class="form-control" name="name" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="mb-3">
              <label class="form-label required">Alamat Surel</label>
              <input type="email" class="form-control" name="email" placeholder="Masukkan alamat surel" required>
            </div>
            <div class="mb-3">
              <label class="form-label required">Jabatan</label>
              <select class="form-select" name="role" required>
                <option value="">Pilih jabatan</option>
                @foreach($roles as $role)
                  <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label required">Kata Sandi</label>
              <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi" required>
            </div>
            <div class="mb-3">
              <label class="form-label required">Konfirmasi Kata Sandi</label>
              <input type="password" class="form-control" name="password_confirmation"
                     placeholder="Konfirmasi kata sandi" required>
            </div>
          </div>
          <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
              Batal
            </a>
            <button type="submit" class="btn btn-primary ms-auto">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                   stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M12 5l0 14"/>
                <path d="M5 12l14 0"/>
              </svg>
              Buat Pengguna Baru
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal for editing user --}}
  <div class="modal modal-blur fade" id="modal-edit-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <form id="edit-user-form" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label required">Nama Lengkap</label>
              <input type="text" class="form-control" name="name" id="edit-name" placeholder="Masukkan nama lengkap"
                     required>
            </div>
            <div class="mb-3">
              <label class="form-label required">Alamat Surel</label>
              <input type="email" class="form-control" name="email" id="edit-email" placeholder="Masukkan alamat surel"
                     required>
            </div>
            <div class="mb-3">
              <label class="form-label required">Jabatan</label>
              <select class="form-select" name="role" id="edit-role" required>
                <option value="">Pilih jabatan</option>
                @foreach($roles as $role)
                  <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Kata Sandi <span class="text-muted">(kosongkan jika tidak ingin mengubah)</span></label>
              <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi baru">
            </div>
            <div class="mb-3">
              <label class="form-label">Konfirmasi Kata Sandi</label>
              <input type="password" class="form-control" name="password_confirmation"
                     placeholder="Konfirmasi kata sandi baru">
            </div>
          </div>
          <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
              Batal
            </a>
            <button type="submit" class="btn btn-primary ms-auto">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                   stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M5 12l5 5l10 -10"/>
              </svg>
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    // Function to change number of items per page
    function changePerPage(value) {
      const url = new URL(window.location.href);
      url.searchParams.set('per_page', value);
      window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function () {
      // Search functionality improvement
      const searchInput = document.getElementById('searchInput');
      const searchForm = document.getElementById('searchForm');

      // Submit search on Enter key press
      searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          searchForm.submit();
        }
      });

      // Submit search after 500ms of no typing
      let searchTimeout;
      searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
          searchForm.submit();
        }, 500);
      });

      // Handle edit user modal
      document.querySelectorAll('.edit-user-btn').forEach(button => {
        button.addEventListener('click', function (e) {
          e.preventDefault();

          const userId = this.getAttribute('data-id');
          const userName = this.getAttribute('data-name');
          const userEmail = this.getAttribute('data-email');
          const userRole = this.getAttribute('data-role');

          // Set form action
          document.getElementById('edit-user-form').action = `/users/${userId}`;

          // Populate form fields
          document.getElementById('edit-name').value = userName;
          document.getElementById('edit-email').value = userEmail;
          document.getElementById('edit-role').value = userRole;

          // Clear password fields
          document.querySelector('#modal-edit-user input[name="password"]').value = '';
          document.querySelector('#modal-edit-user input[name="password_confirmation"]').value = '';
        });
      });

      // Auto-hide flash messages after 5 seconds
      setTimeout(function () {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function (alert) {
          const closeButton = alert.querySelector('.btn-close');
          if (closeButton) {
            closeButton.click();
          }
        });
      }, 5000);
    });
  </script>
@endpush
