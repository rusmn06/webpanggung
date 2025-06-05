@extends('layouts.main')

@section('title', 'Detail Pengajuan Saya Ke-' . ($item->user_sequence_number ?? $item->id))

@push('styles')
<style>
    /* ... (Gaya signature-box, verification-title, info-list-condensed, card-detail-section, card-alert-custom tetap sama) ... */
    .signature-box {
        border: 1px dashed #ccc; padding: 15px; text-align: center; margin-top: 10px; min-height: 120px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        background-color: #f8f9fa; border-radius: .25rem;
    }
    .signature-box img { max-height: 80px; max-width: 100%; border: 1px solid #eee; }
    .signature-box .signer-name { margin-top: 8px; font-weight: bold; font-size: 0.85rem; }
    .verification-title { font-size: 0.9rem; font-weight: bold; color: #495057; margin-bottom: 5px; text-transform: uppercase; }
    .info-list-condensed dt { font-weight: normal; color: #5a5c69; padding-top: 0.25rem; padding-bottom: 0.25rem;}
    .info-list-condensed dd { font-weight: 500; padding-top: 0.25rem; padding-bottom: 0.25rem;}
    .card-detail-section .card-title { color: #007bff !important; }
    .info-list-condensed hr.item-divider { margin-top: 0.5rem; margin-bottom: 0.5rem; border-top: 1px solid rgba(0,0,0,.05); }


    .card-alert-custom {
        background-color: #e9ecef; border: 1px solid #ced4da; padding: 1rem;
        border-radius: .35rem; font-size: 0.875rem; line-height: 1.5;
    }
    .card-alert-custom .alert-heading { font-size: 1rem; font-weight: 500; color: #495057; }
    .card-alert-custom .icon-warning { font-size: 1.1rem; margin-right: 8px; color: #6c757d; }

    /* PERUBAHAN 1: Gaya untuk Kartu Export dengan Ikon Folder Responsif */
    .card-export-with-icon .card-body {
        padding: 0.8rem 1rem; /* Padding disesuaikan agar lebih pas */
    }
    .card-export-with-icon .icon-export-folder-wrapper .fa-folder-open {
        color: #28a745; /* Warna hijau untuk ikon folder */
        /* Ukuran diatur via kelas fa-2x / fa-3x di HTML */
    }
    .card-export-with-icon .export-text {
        font-size: 0.85rem; /* Ukuran font teks deskripsi sedikit lebih kecil */
        color: #495057;
        margin-bottom: 0.75rem;
    }
    .card-export-with-icon .btn-download-excel {
        font-size: 0.9rem;
    }

    /* Styling tabel anggota keluarga (tetap sama) */
    .table-anggota-keluarga th {
        vertical-align: middle !important; text-align: center; font-size: 0.78rem;
        padding: 0.5rem 0.3rem; white-space: normal;
    }
    .table-anggota-keluarga td { vertical-align: middle !important; font-size: 0.85rem; padding: 0.4rem; }
    .table-anggota-keluarga th.th-nik { width: 190px; min-width: 190px; }
    .table-anggota-keluarga td.td-nik {
        font-family: monospace; letter-spacing: 1.5px; white-space: nowrap;
        text-align: center; width: 190px; min-width: 190px;
    }
    .alert-petunjuk-tabel ul { font-size: 0.8rem; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Judul Halaman dan Tombol Kembali (Tidak Berubah) --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <h1 class="h3 text-gray-800 mb-0">
                Detail Pengajuan Ke-{{ $item->user_sequence_number ?? '??' }}
            </h1>
            @php
                $statusText = $item->status_validasi_text ?? ucfirst($item->status_validasi);
                $badgeClass = 'badge-light text-dark border';
                if ($item->status_validasi === 'pending') $badgeClass = 'badge-warning text-dark';
                if ($item->status_validasi === 'validated') $badgeClass = 'badge-success';
                if ($item->status_validasi === 'rejected') $badgeClass = 'badge-danger';
            @endphp
            <span class="badge {{ $badgeClass }} p-2 ml-2" style="font-size: 0.9rem;">{{ $statusText }}</span>
        </div>
        <div>
            <a href="{{ route('tenagakerja.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>

    {{-- BARIS UTAMA: Info Pengajuan (Kiri) & Kolom Kanan (Alert + Export) --}}
    <div class="row d-flex align-items-stretch">
        {{-- Kolom Kiri: Informasi Pengajuan --}}
        <div class="col-lg-7 mb-4 mb-lg-0 d-flex">
            <div class="card shadow-sm card-detail-section h-100 w-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold card-title">Informasi Pengajuan</h6>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('dddd, D MMMM GGGG') }}
                    </small>
                </div>
                <div class="card-body p-3">
                    <dl class="row info-list-condensed mb-0">
                        <dt class="col-sm-5 col-lg-4">Nama Pendata</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->nama_pendata }}</dd>
                        <dt class="col-sm-5 col-lg-4">Nama Responden</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->nama_responden }}</dd>

                        {{-- PERUBAHAN 2: Garis pemisah ditambahkan di sini --}}
                        <div class="col-12"><hr class="item-divider"></div>

                        <dt class="col-sm-5 col-lg-4">Provinsi</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->provinsi }}</dd>
                        <dt class="col-sm-5 col-lg-4">Kota / Kab</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->kabupaten }}</dd>
                        <dt class="col-sm-5 col-lg-4">Kecamatan</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->kecamatan }}</dd>
                        <dt class="col-sm-5 col-lg-4">Desa / Kelurahan</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->desa }}</dd>
                        <dt class="col-sm-5 col-lg-4">RT / RW</dt>
                        <dd class="col-sm-7 col-lg-8">{{ $item->rt }} / {{ $item->rw }}</dd>
                        {{--
                        <dt class="col-sm-5 col-lg-4">ID Sistem</dt>
                        <dd class="col-sm-7 col-lg-8">RT-{{ $item->id }}</dd>
                        --}}
                    </dl>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Alert Catatan Penting & Tombol Export --}}
        <div class="col-lg-5 d-flex flex-column">
            {{-- Kartu Alert (Tidak Berubah) --}}
            <div class="card-alert-custom mb-3 shadow-sm">
                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle icon-warning"></i> Catatan Penting</h5>
                <p class="mb-1">Mohon luangkan waktu sejenak untuk <strong>memverifikasi kembali semua detail data</strong> yang telah Anda ajukan. Pastikan tidak ada kekeliruan atau informasi yang terlewat.</p>
                <p class="mb-0">Jika Anda menemukan ketidaksesuaian atau memerlukan perubahan data, jangan ragu untuk segera <strong>menghubungi Administrator Sistem</strong> kami. Keakuratan data Anda sangat penting.</p>
            </div>

            {{-- PERUBAHAN 1: Kartu Export dengan Ikon Folder Responsif --}}
            <div class="card shadow-sm card-export-with-icon flex-grow-1">
                <div class="card-body">
                    <div class="row align-items-center">
                        {{-- Ikon Folder: Hanya tampil di MD ke atas, mengambil sekitar 1/4 - 1/3 lebar kolom kanan --}}
                        <div class="col-md-3 d-none d-md-flex justify-content-center align-items-center icon-export-folder-wrapper">
                            <i class="fas fa-folder-open fa-3x"></i> {{-- Ukuran ikon bisa fa-2x atau fa-3x --}}
                        </div>
                        {{-- Teks & Tombol: Mengambil lebar penuh di SM, dan sisa lebar di MD ke atas --}}
                        <div class="col-12 col-md-9 {{-- @extends('layouts.main')

@section('title','DetailPengajuanSayaKe-'.($item->user_sequence_number??$item->id))

@push('styles')
<style>
/*...(Gayasignature-box,verification-title,info-list-condensed,card-detail-section,card-alert-customtetapsama)...*/
.signature-box
border:1pxdashed#ccc;padding:15px;text-align:center;margin-top:10px;min-height:120px;
display:flex;flex-direction:column;align-items:center;justify-content:center;
background-color:#f8f9fa;border-radius:.25rem;

.signature-boximgmax-height:80px;max-width:100%;border:1pxsolid#eee;
.signature-box.signer-namemargin-top:8px;font-weight:bold;font-size:0.85rem;
.verification-titlefont-size:0.9rem;font-weight:bold;color:#495057;margin-bottom:5px;text-transform:uppercase;
.info-list-condenseddtfont-weight:normal;color:#5a5c69;padding-top:0.25rem;padding-bottom:0.25rem;
.info-list-condensedddfont-weight:500;padding-top:0.25rem;padding-bottom:0.25rem;
.card-detail-section.card-titlecolor:#007bff!important;
.info-list-condensedhr.item-dividermargin-top:0.5rem;margin-bottom:0.5rem;border-top:1pxsolidrgba(0,0,0,.05);


.card-alert-custom
background-color:#e9ecef;border:1pxsolid#ced4da;padding:1rem;
border-radius:.35rem;font-size:0.875rem;line-height:1.5;

.card-alert-custom.alert-headingfont-size:1rem;font-weight:500;color:#495057;
.card-alert-custom.icon-warningfont-size:1.1rem;margin-right:8px;color:#6c757d;

/*PERUBAHAN1:GayauntukKartuExportdenganIkonFolderResponsif*/
.card-export-with-icon.card-body
padding:0.8rem1rem;/*Paddingdisesuaikanagarlebihpas*/

.card-export-with-icon.icon-export-folder-wrapper.fa-folder-open
color:#28a745;/*Warnahijauuntukikonfolder*/
/*Ukurandiaturviakelasfa-2x/fa-3xdiHTML*/

.card-export-with-icon.export-text
font-size:0.85rem;/*Ukuranfontteksdeskripsisedikitlebihkecil*/
color:#495057;
margin-bottom:0.75rem;

.card-export-with-icon.btn-download-excel
font-size:0.9rem;


/*Stylingtabelanggotakeluarga(tetapsama)*/
.table-anggota-keluargath
vertical-align:middle!important;text-align:center;font-size:0.78rem;
padding:0.5rem0.3rem;white-space:normal;

.table-anggota-keluargatdvertical-align:middle!important;font-size:0.85rem;padding:0.4rem;
.table-anggota-keluargath.th-nikwidth:190px;min-width:190px;
.table-anggota-keluargatd.td-nik
font-family:monospace;letter-spacing:1.5px;white-space:nowrap;
text-align:center;width:190px;min-width:190px;

.alert-petunjuk-tabelulfont-size:0.8rem;
</style>
@endpush

@section('content')
<divclass="container-fluid">
JudulHalamandanTombolKembali(TidakBerubah)
<divclass="d-sm-flexalign-items-centerjustify-content-betweenmb-4">
<divclass="d-flexalign-items-center">
<h1class="h3text-gray-800mb-0">
DetailPengajuanKe-$item->user_sequence_number??'??'
</h1>
@php
$statusText=$item->status_validasi_text??ucfirst($item->status_validasi);
$badgeClass='badge-lighttext-darkborder';
if($item->status_validasi==='pending')$badgeClass='badge-warningtext-dark';
if($item->status_validasi==='validated')$badgeClass='badge-success';
if($item->status_validasi==='rejected')$badgeClass='badge-danger';
@endphp
<spanclass="badge$badgeClassp-2ml-2"style="font-size:0.9rem;">$statusText</span>
</div>
<div>
<ahref="route('tenagakerja.index')"class="btnbtn-smbtn-outline-secondaryshadow-sm">
<iclass="fasfa-arrow-leftfa-sm"></i>KembalikeRiwayat
</a>
</div>
</div>

BARISUTAMA:InfoPengajuan(Kiri)&KolomKanan(Alert+Export)
<divclass="rowd-flexalign-items-stretch">
KolomKiri:InformasiPengajuan
<divclass="col-lg-7mb-4mb-lg-0d-flex">
<divclass="cardshadow-smcard-detail-sectionh-100w-100">
<divclass="card-headerpy-3d-flexjustify-content-betweenalign-items-center">
<h6class="m-0font-weight-boldcard-title">InformasiPengajuan</h6>
<smallclass="text-muted">
\Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('dddd,DMMMMGGGG')
</small>
</div>
<divclass="card-bodyp-3">
<dlclass="rowinfo-list-condensedmb-0">
<dtclass="col-sm-5col-lg-4">NamaPendata</dt>
<ddclass="col-sm-7col-lg-8">$item->nama_pendata</dd>
<dtclass="col-sm-5col-lg-4">NamaResponden</dt>
<ddclass="col-sm-7col-lg-8">$item->nama_responden</dd>

PERUBAHAN2:Garispemisahditambahkandisini
<divclass="col-12"><hrclass="item-divider"></div>

<dtclass="col-sm-5col-lg-4">Provinsi</dt>
<ddclass="col-sm-7col-lg-8">$item->provinsi</dd>
<dtclass="col-sm-5col-lg-4">Kota/Kab</dt>
<ddclass="col-sm-7col-lg-8">$item->kabupaten</dd>
<dtclass="col-sm-5col-lg-4">Kecamatan</dt>
<ddclass="col-sm-7col-lg-8">$item->kecamatan</dd>
<dtclass="col-sm-5col-lg-4">Desa/Kelurahan</dt>
<ddclass="col-sm-7col-lg-8">$item->desa</dd>
<dtclass="col-sm-5col-lg-4">RT/RW</dt>
<ddclass="col-sm-7col-lg-8">$item->rt/$item->rw</dd>

<dtclass="col-sm-5col-lg-4">IDSistem</dt>
<ddclass="col-sm-7col-lg-8">RT-$item->id</dd>

</dl>
</div>
</div>
</div>

KolomKanan:AlertCatatanPenting&TombolExport
<divclass="col-lg-5d-flexflex-column">
KartuAlert(TidakBerubah)
<divclass="card-alert-custommb-3shadow-sm">
<h5class="alert-heading"><iclass="fasfa-exclamation-triangleicon-warning"></i>CatatanPenting</h5>
<pclass="mb-1">Mohonluangkanwaktusejenakuntuk<strong>memverifikasikembalisemuadetaildata</strong>yangtelahAndaajukan.Pastikantidakadakekeliruanatauinformasiyangterlewat.</p>
<pclass="mb-0">JikaAndamenemukanketidaksesuaianataumemerlukanperubahandata,janganraguuntuksegera<strong>menghubungiAdministratorSistem</strong>kami.KeakuratandataAndasangatpenting.</p>
</div>

PERUBAHAN1:KartuExportdenganIkonFolderResponsif
<divclass="cardshadow-smcard-export-with-iconflex-grow-1">
<divclass="card-body">
<divclass="rowalign-items-center">
IkonFolder:HanyatampildiMDkeatas,mengambilsekitar1/4-1/3lebarkolomkanan
<divclass="col-md-3d-noned-md-flexjustify-content-centeralign-items-centericon-export-folder-wrapper">
<iclass="fasfa-folder-openfa-3x"></i>Ukuranikonbisafa-2xataufa-3x
</div>
Teks&Tombol:MengambillebarpenuhdiSM,dansisalebardiMDkeatas
<divclass="col-12col-md-9text-centertext-md-leftpl-md-0">text-md-leftdanpl-md-0untukdesktop
<pclass="export-text">
UnduhdatapengajuanlengkapdalamformatExcel(XLSX).
</p>
<ahref="route('tenagakerja.exportExcel',['id'=>$item->id])"class="btnbtn-successbtn-download-excel">
<iclass="fasfa-downloadmr-1"></i>UnduhFile
</a>
</div>
</div>
</div>
</div>
</div>
</div>

DataAnggotaKeluarga(TidakBerubah)
<divclass="rowmt-4">
<divclass="col-12mb-4">
<divclass="cardshadow-smcard-detail-section">
<divclass="card-headerpy-3">
<h6class="m-0font-weight-boldcard-title">KETERANGANSTATUSPEKERJAAN</h6>
</div>
<divclass="card-body">
<divclass="alertalert-secondaryalert-petunjuk-tabel"role="alert">
<h6class="alert-heading"style="font-size:0.9rem;"><iclass="fasfa-info-circle"></i>PetunjukSingkatanKolomTabel:</h6>
<ulclass="mb-0pl-3">
<li><strong>Hub.KRT:</strong>HubungandenganKepalaRumahTangga</li>
<li><strong>NUK:</strong>NomorUrutAnggotadalamKeluarga</li>
<li><strong>HDKK:</strong>HubunganDenganKepalaKeluarga</li>
<li><strong>J.Kelamin:</strong>JenisKelamin</li>
<li><strong>Sts.Kawin:</strong>StatusPerkawinan</li>
<li><strong>Sts.Kerja:</strong>StatusPekerjaan</li>
<li><strong>Jns.Kerja:</strong>JenisPekerjaanUtama</li>
<li><strong>SubJns.Kerja:</strong>SubJenisPekerjaan</li>
<li><strong>Pddk.Akhir:</strong>PendidikanTerakhir</li>
<li><strong>Pendapatan/bln:</strong>PendapatanRata-rataperBulan</li>
</ul>
</div>
<divclass="table-responsivemt-3">
<tableclass="tabletable-borderedtable-hovertable-smtable-anggota-keluarga">
<theadclass="thead-light">
<tr>
<th>No</th>
<th>Nama</th>
<thclass="th-nik">NIK</th>
<th>Hub.KRT</th>
<th>NUK</th>
<th>HDKK</th>
<th>J.Kelamin</th>
<th>Sts.Kawin</th>
<th>Sts.Kerja</th>
<th>Jns.Kerja</th>
<th>SubJns.Kerja</th>
<th>Pddk.Akhir</th>
<th>Pendapatan/bln</th>
</tr>
</thead>
<tbody>
@forelse($item->anggotaKeluargaas$anggota)
<tr>
<tdclass="text-center">$loop->iteration</td>
<td>$anggota->nama</td>
<tdclass="td-nik">$anggota->formatted_nik</td>
<tdclass="text-center">$anggota->hdkrt_text</td>
<tdclass="text-center">$anggota->nuk??'-'</td>
<tdclass="text-center">$anggota->hdkk_text??'-'</td>
<tdclass="text-center">$anggota->kelamin_text</td>
<tdclass="text-center">$anggota->status_perkawinan_text</td>
<tdclass="text-center">$anggota->status_pekerjaan_text</td>
<tdclass="text-center">$anggota->jenis_pekerjaan_text</td>
<tdclass="text-center">$anggota->sub_jenis_pekerjaan_text</td>
<tdclass="text-center">$anggota->pendidikan_terakhir_text</td>
<tdclass="text-right">$anggota->pendapatan_per_bulan_text</td>
</tr>
@empty
<tr><tdcolspan="13"class="text-center">Tidakadadataanggotakeluarga.</td></tr>
@endforelse
</tbody>
</table>
<smallclass="text-mutedmt-2d-block">*KolomSubJenisPekerjaan&Pendapatan/blnakanterisijikadatatersediadansesuaidengankodepadasistem.</small>
</div>
</div>
</div>
</div>
</div>

KartuVerifikasidanValidasi(TidakBerubah)
<divclass="row">
<divclass="col-12mb-4">
<divclass="cardshadow-smcard-detail-section">
<divclass="card-headerpy-3d-flexflex-rowalign-items-centerjustify-content-between">
<h6class="m-0font-weight-boldcard-title">VerifikasidanValidasi</h6>
</div>
<divclass="card-body">
<divclass="row">
<divclass="col-md-6mb-3mb-md-0">
<pclass="verification-titletext-center">DISERAHKANOLEHPENDATA</p>
<divclass="text-centermb-2">
Tgl/Bulan/Tahun:<strong>$item->tgl_pembuatan?\Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD/MM/GGGG'):'-/-/'</strong>
</div>
<divclass="signature-box">
@if($item->ttd_pendata)
<imgsrc="asset('storage/ttd/pendata/'.$item->ttd_pendata)"alt="TTDPendata">
<pclass="signer-name">($item->nama_pendata??'NamaPendataTidakAda')</p>
@else
<pclass="text-muted"><em>BelumadaTTDPendata</em></p>
<pclass="signer-name">($item->nama_pendata??'NamaPendataTidakAda')</p>
@endif
</div>
</div>
<divclass="col-md-6">
<pclass="verification-titletext-center">DIVERIFIKASIKEPALADUSUN</p>
@if($item->status_validasi!='pending'&&$item->admin_tgl_validasi)
<divclass="text-centermb-2">
Tgl/Bulan/Tahun:<strong>\Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('DD/MM/GGGG')</strong>
</div>
<divclass="signature-box">
@if($item->admin_ttd_kepala_dusun)
<imgsrc="asset('storage/ttd/admin/'.$item->admin_ttd_kepala_dusun)"alt="TTDKepalaDusun">
<pclass="signer-name">($item->admin_nama_kepaladusun??'NamaKepalaDusunTidakAda')</p>
@elseif($item->admin_ttd_pendata&&!$item->admin_ttd_kepala_dusun)
<imgsrc="asset('storage/ttd/admin/'.$item->admin_ttd_pendata)"alt="TTDVerifikator">
<pclass="signer-name">($item->admin_nama_kepaladusun??'NamaVerifikatorTidakAda')</p>
@else
<pclass="text-muted"><em>BelumadaTTDVerifikator</em></p>
<pclass="signer-name">($item->admin_nama_kepaladusun??'NamaVerifikatorTidakAda')</p>
@endif
</div>
@else
<divclass="text-centermb-2">
Tgl/Bulan/Tahun:<strong>-/-/</strong>
</div>
<divclass="signature-box">
<pclass="text-muted"><em>BelumDiverifikasi</em></p>
<pclass="signer-name">(..................................................)</p>
</div>
@endif
</div>
</div>
@if($item->status_validasi=='rejected'&&$item->admin_catatan_validasi)
<divclass="mt-3">
<h6class="text-dark">CatatanPenolakan:</h6>
<pclass="text-danger"><em>$item->admin_catatan_validasi</em></p>
</div>
@elseif($item->status_validasi=='validated'&&$item->admin_catatan_validasi)
<divclass="mt-3">
<h6class="text-dark">CatatanValidasi:</h6>
<pclass="text-muted"><em>$item->admin_catatan_validasi</em></p>
</div>
@endif
</div>
</div>
</div>
</div>

BootstrapModaluntukNotifikasiSukses(TidakBerubah)
@if(session('show_success_modal'))
<divclass="modalfade"id="successModal"tabindex="-1"aria-labelledby="successModalLabel"aria-hidden="true"data-backdrop="static"data-keyboard="false">
<divclass="modal-dialogmodal-dialog-centered">
<divclass="modal-content">
<divclass="modal-headerbg-successtext-white">
<h5class="modal-title"id="successModalLabel">
<iclass="fasfa-check-circlemr-2"></i>session('success_message_title','Berhasil!')
</h5>
</div>
<divclass="modal-body">
<divclass="text-centerpy-3">
<pstyle="font-size:1.1rem;">session('success_message_body','DataAndatelahberhasildiproses.')</p>
</div>
</div>
<divclass="modal-footerjustify-content-center">
<ahref="route('tenagakerja.index')"class="btnbtn-outline-primary">
<iclass="fasfa-list-alt"></i>LihatRiwayatPengajuan
</a>
<ahref="route('tkw.step1')"class="btnbtn-primary">
<iclass="fasfa-plus-circle"></i>AjukanDataLain
</a>
</div>
</div>
</div>
</div>
@endif

</div>
@endsection

@push('scripts')
@if(session('show_success_modal'))
<script>
if(typeofjQuery=='undefined')
console.error('jQuerytidaktermuat!ModalBootstrapmembutuhkanjQuery.');
else
$(document).ready(function()
$('#successModal').modal('show');
);

</script>
@endif
@endpush --}}left dan pl-md-0 untuk desktop --}}
                            <p class="export-text">
                                Unduh data pengajuan lengkap dalam format Excel (XLSX).
                            </p>
                            <a href="{{ route('tenagakerja.exportExcel', ['id' => $item->id]) }}" class="btn btn-success btn-download-excel">
                                <i class="fas fa-download mr-1"></i>Unduh File
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Anggota Keluarga (Tidak Berubah) --}}
    <div class="row mt-4">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold card-title">KETERANGAN STATUS PEKERJAAN</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary alert-petunjuk-tabel" role="alert">
                        <h6 class="alert-heading" style="font-size: 0.9rem;"><i class="fas fa-info-circle"></i> Petunjuk Singkatan Kolom Tabel:</h6>
                        <ul class="mb-0 pl-3">
                            <li><strong>Hub. KRT:</strong> Hubungan dengan Kepala Rumah Tangga</li>
                            <li><strong>NUK:</strong> Nomor Urut Anggota dalam Keluarga</li>
                            <li><strong>HDKK:</strong> Hubungan Dengan Kepala Keluarga</li>
                            <li><strong>J. Kelamin:</strong> Jenis Kelamin</li>
                            <li><strong>Sts. Kawin:</strong> Status Perkawinan</li>
                            <li><strong>Sts. Kerja:</strong> Status Pekerjaan</li>
                            <li><strong>Jns. Kerja:</strong> Jenis Pekerjaan Utama</li>
                            <li><strong>Sub Jns. Kerja:</strong> Sub Jenis Pekerjaan</li>
                            <li><strong>Pddk. Akhir:</strong> Pendidikan Terakhir</li>
                            <li><strong>Pendapatan/bln:</strong> Pendapatan Rata-rata per Bulan</li>
                        </ul>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-hover table-sm table-anggota-keluarga">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th class="th-nik">NIK</th>
                                    <th>Hub. KRT</th>
                                    <th>NUK</th>
                                    <th>HDKK</th>
                                    <th>J. Kelamin</th>
                                    <th>Sts. Kawin</th>
                                    <th>Sts. Kerja</th>
                                    <th>Jns. Kerja</th>
                                    <th>Sub Jns. Kerja</th>
                                    <th>Pddk. Akhir</th>
                                    <th>Pendapatan/bln</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($item->anggotaKeluarga as $anggota)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $anggota->nama }}</td>
                                        <td class="td-nik">{{ $anggota->formatted_nik }}</td>
                                        <td class="text-center">{{ $anggota->hdkrt_text }}</td>
                                        <td class="text-center">{{ $anggota->nuk ?? '-' }}</td>
                                        <td class="text-center">{{ $anggota->hdkk_text ?? '-' }}</td>
                                        <td class="text-center">{{ $anggota->kelamin_text }}</td>
                                        <td class="text-center">{{ $anggota->status_perkawinan_text }}</td>
                                        <td class="text-center">{{ $anggota->status_pekerjaan_text }}</td>
                                        <td class="text-center">{{ $anggota->jenis_pekerjaan_text }}</td>
                                        <td class="text-center">{{ $anggota->sub_jenis_pekerjaan_text }}</td>
                                        <td class="text-center">{{ $anggota->pendidikan_terakhir_text }}</td>
                                        <td class="text-right">{{ $anggota->pendapatan_per_bulan_text }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="13" class="text-center">Tidak ada data anggota keluarga.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <small class="text-muted mt-2 d-block">* Kolom Sub Jenis Pekerjaan & Pendapatan/bln akan terisi jika data tersedia dan sesuai dengan kode pada sistem.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Verifikasi dan Validasi (Tidak Berubah) --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm card-detail-section">
                 <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold card-title">Verifikasi dan Validasi</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <p class="verification-title text-center">DISERAHKAN OLEH PENDATA</p>
                            <div class="text-center mb-2">
                                Tgl/Bulan/Tahun: <strong>{{ $item->tgl_pembuatan ? \Carbon\Carbon::parse($item->tgl_pembuatan)->isoFormat('DD / MM / GGGG') : '- / - / ----' }}</strong>
                            </div>
                            <div class="signature-box">
                                @if($item->ttd_pendata)
                                    <img src="{{ asset('storage/ttd/pendata/'.$item->ttd_pendata) }}" alt="TTD Pendata">
                                    <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata Tidak Ada' }} )</p>
                                @else
                                    <p class="text-muted"><em>Belum ada TTD Pendata</em></p>
                                    <p class="signer-name">( {{ $item->nama_pendata ?? 'Nama Pendata Tidak Ada' }} )</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                             <p class="verification-title text-center">DIVERIFIKASI KEPALA DUSUN</p>
                            @if($item->status_validasi != 'pending' && $item->admin_tgl_validasi)
                                <div class="text-center mb-2">
                                     Tgl/Bulan/Tahun: <strong>{{ \Carbon\Carbon::parse($item->admin_tgl_validasi)->isoFormat('DD / MM / GGGG') }}</strong>
                                </div>
                                <div class="signature-box">
                                    @if($item->admin_ttd_kepala_dusun)
                                        <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_kepala_dusun) }}" alt="TTD Kepala Dusun">
                                        <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Kepala Dusun Tidak Ada' }} )</p>
                                    @elseif($item->admin_ttd_pendata && !$item->admin_ttd_kepala_dusun)
                                        <img src="{{ asset('storage/ttd/admin/'.$item->admin_ttd_pendata) }}" alt="TTD Verifikator">
                                        <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Verifikator Tidak Ada' }} )</p>
                                    @else
                                        <p class="text-muted"><em>Belum ada TTD Verifikator</em></p>
                                        <p class="signer-name">( {{ $item->admin_nama_kepaladusun ?? 'Nama Verifikator Tidak Ada' }} )</p>
                                    @endif
                                </div>
                            @else
                                <div class="text-center mb-2">
                                    Tgl/Bulan/Tahun: <strong>- / - / ----</strong>
                                </div>
                                <div class="signature-box">
                                    <p class="text-muted"><em>Belum Diverifikasi</em></p>
                                    <p class="signer-name">( .................................................. )</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($item->status_validasi == 'rejected' && $item->admin_catatan_validasi)
                    <div class="mt-3">
                        <h6 class="text-dark">Catatan Penolakan:</h6>
                        <p class="text-danger"><em>{{ $item->admin_catatan_validasi }}</em></p>
                    </div>
                    @elseif($item->status_validasi == 'validated' && $item->admin_catatan_validasi)
                     <div class="mt-3">
                        <h6 class="text-dark">Catatan Validasi:</h6>
                        <p class="text-muted"><em>{{ $item->admin_catatan_validasi }}</em></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap Modal untuk Notifikasi Sukses (Tidak Berubah) --}}
    @if(session('show_success_modal'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success_message_title', 'Berhasil!') }}
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="text-center py-3">
                        <p style="font-size: 1.1rem;">{{ session('success_message_body', 'Data Anda telah berhasil diproses.') }}</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="{{ route('tenagakerja.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list-alt"></i> Lihat Riwayat Pengajuan
                    </a>
                    <a href="{{ route('tkw.step1') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Ajukan Data Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
@if(session('show_success_modal'))
<script>
    if (typeof jQuery == 'undefined') {
        console.error('jQuery tidak termuat! Modal Bootstrap membutuhkan jQuery.');
    } else {
        $(document).ready(function(){
            $('#successModal').modal('show');
        });
    }
</script>
@endif
@endpush