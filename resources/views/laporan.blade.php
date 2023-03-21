@extends('layouts.main')

@section('container')
<!-- /.row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Laporan</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Pelapor</th>
                            <th>Judul/Permasalahan</th>
                            <th>Solusi</th>
                            <th>Ditugaskan Kepada Teknisi</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $key => $report)
                        <tr data-id="{{ $report->id }}">
                            <td>{{ ++$key }}</td>
                            <td>{{ $report->tgl }}</td>
                            <td>{{ $report->userPelapor->name }}</td>
                            <td>{{ $report->judul }}</td>
                            <td>
                                @if (is_null($report->solusi))
                                    <span class="badge badge-warning">Belum ada solusi</span>
                                @else
                                    {{ $report->solusi }}
                                @endif
                            </td>
                            <td>
                                @if (is_null($report->userTeknisi))
                                    <span class="badge badge-warning">Belum ada teknisi</span>
                                @else
                                    {{ $report->userTeknisi->name }}
                                @endif
                            </td>
                            <td><span class="badge badge-{{ ($report->status == 'open') ? 'success' : 'danger' }}">{{ $report->status }}</span></td>
                            <td>
                                @if (Auth::user()->role == 'admin')
                                @if (is_null($report->teknisi))
                                <button class="btn btn-primary btn-sm btn-teknisi" data-toggle="modal" data-target="#modal-teknisi">Assign Teknisi</button>
                                @else
                                    -
                                @endif
                                @elseif (Auth::user()->role == 'teknisi')
                                    @if (is_null($report->solusi))
                                        @if (Auth::user()->id == $report->teknisi)
                                        <button class="btn btn-primary btn-sm btn-edit" data-toggle="modal" data-target="#modal-solusi">Tambah solusi</button>
                                        @else
                                            -
                                        @endif
                                    @else
                                    -
                                    @endif
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection

@section('modal_laporan')
<div class="modal fade" id="modal-laporan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buat Laporan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="laporanCrud">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <div class="input-group date" id="tglLaporan" data-target-input="nearest">
                            <input name="tgl" type="text" class="form-control datetimepicker-input" data-target="#tglLaporan" required/>
                            <div class="input-group-append" data-target="#tglLaporan" data-toggle="datetimepicker">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Judul/Permasalahan</label>
                        <input name="judul" type="text" class="form-control" id="judul" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('modal_solusi')
<div class="modal fade" id="modal-solusi">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Solusi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="laporanCrud">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Solusi</label>
                        <textarea class="form-control" name="solusi" id="solusi" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('modal_teknisi')
<div class="modal fade" id="modal-teknisi">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Assign</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="assignTeknisi">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Teknisi</label>
                        <select name="teknisi" class="form-control">
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('modal_excel')
<div class="modal fade" id="modal-excel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Import Excel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="importExcel" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputPassword1">File</label>
                        <input type="file" name="file" class="form-control" id="file">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection