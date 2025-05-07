@extends('layouts.main')

@section('content')

<table class="table table-bordered">
  <tbody>
    <tr>
      <th>ID</th>
      <td>{{ $item->id }}</td>
    </tr>
    <tr>
      <th>Provinsi</th>
      <td>{{ $item->provinsi }}</td>
    </tr>
    <tr>
      <th>Kabupaten</th>
      <td>{{ $item->kabupaten }}</td>
    </tr>
    <tr>
      <th>Kecamatan</th>
      <td>{{ $item->kecamatan }}</td>
    </tr>
    <tr>
      <th>Desa</th>
      <td>{{ $item->desa }}</td>
    </tr>
    <tr>
      <th>RT/RW</th>
      <td>{{ $item->rt_rw }}</td>
    </tr>
    <tr>
      <th>Tgl Pembuatan</th>
      <td>{{ $item->tgl_pembuatan }}</td>
    </tr>
    <tr>
      <th>Nama Pendata</th>
      <td>{{ $item->nama_pendata }}</td>
    </tr>
    <tr>
      <th>Nama Responden</th>
      <td>{{ $item->nama_responden }}</td>
    </tr>
    <tr>
      <th>Nama</th>
      <td>{{ $item->nama }}</td>
    </tr>
    <tr>
      <th>NIK</th>
      <td>{{ $item->nik }}</td>
    </tr>
    <tr>
      <th>HDKRT</th>
      <td>{{ $item->hdkrt }}</td>
    </tr>
    <tr>
      <th>NUK</th>
      <td>{{ $item->nuk }}</td>
    </tr>
    <tr>
      <th>HDKK</th>
      <td>{{ $item->hdkk }}</td>
    </tr>
    <tr>
      <th>Kelamin</th>
      <td>{{ $item->kelamin == '1' ? 'Pria' : 'Wanita' }}</td>
    </tr>
    <tr>
      <th>Status Perkawinan</th>
      <td>{{ $item->status_perkawinan }}</td>
    </tr>
    <tr>
      <th>Status Pekerjaan</th>
      <td>{{ $item->status_pekerjaan }}</td>
    </tr>
    <tr>
      <th>Jenis Pekerjaan</th>
      <td>{{ $item->jenis_pekerjaan }}</td>
    </tr>
    <tr>
      <th>Sub Jenis Pekerjaan</th>
      <td>{{ $item->sub_jenis_pekerjaan }}</td>
    </tr>
    <tr>
      <th>Pendidikan Terakhir</th>
      <td>{{ $item->pendidikan_terakhir }}</td>
    </tr>
    <tr>
      <th>Pendapatan per Bulan</th>
      <td>{{ $item->pendapatan_per_bulan }}</td>
    </tr>
    <tr>
      <th>Rekap JART</th>
      <td>{{ $item->jart }}</td>
    </tr>
    <tr>
      <th>Rekap JART_AB</th>
      <td>{{ $item->jart_ab }}</td>
    </tr>
    <tr>
      <th>Rekap JART_TB</th>
      <td>{{ $item->jart_tb }}</td>
    </tr>
    <tr>
      <th>Rekap JART_MS</th>
      <td>{{ $item->jart_ms }}</td>
    </tr>
    <tr>
      <th>JPR2RTP</th>
      <td>{{ $item->jpr2rtp }}</td>
    </tr>
    <tr>
      <th>Tgl Verifikasi</th>
      <td>{{ $item->verif_tgl_pembuatan }}</td>
    </tr>
    <tr>
      <th>Nama Pendata Verifikasi</th>
      <td>{{ $item->verif_nama_pendata }}</td>
    </tr>
    <tr>
      <th>TTD Pendata</th>
      <td>
        @if($item->ttd_pendata)
          <img src="{{ asset('storage/'.$item->ttd_pendata) }}" alt="TTD Pendata" style="max-height:150px;">
        @endif
      </td>
    </tr>
  </tbody>
</table>


@endsection