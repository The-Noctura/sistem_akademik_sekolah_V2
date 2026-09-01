<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $programs = $this->programsData();
        $news = $this->newsData();
        return view('public.home', compact('programs', 'news'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function programs()
    {
        $programs = $this->programsData();
        return view('public.programs', compact('programs'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function facilities()
    {
        return view('public.facilities');
    }

    public function news()
    {
        $news = $this->newsData();
        return view('public.news', compact('news'));
    }

    private function programsData()
    {
        return [
            ['code'=>'EIND','name'=>'Teknik Elektronika Industri','icon'=>'ti-cpu','color'=>'blue','desc'=>'Penguasaan elektronika industri, PLC, sensor & IoT untuk otomatisasi manufaktur.'],
            ['code'=>'TPM','name'=>'Teknik Pemesinan','icon'=>'ti-settings','color'=>'slate','desc'=>'Keterampilan pemesinan, CNC & manufaktur presisi untuk industri mesin.'],
            ['code'=>'TGM','name'=>'Teknik Gambar Mesin','icon'=>'ti-ruler-measure','color'=>'emerald','desc'=>'Desain & gambar teknik mesin 2D/3D dengan CAD profesional.'],
            ['code'=>'TKRO','name'=>'Teknik Kendaraan Ringan Otomotif','icon'=>'ti-car','color'=>'red','desc'=>'Perawatan & perbaikan kendaraan ringan modern, EFI & hybrid.'],
            ['code'=>'TEKS','name'=>'Teknologi Penyempurnaan Tekstil','icon'=>'ti-shirt','color'=>'amber','desc'=>'Teknologi proses tekstil dari pemintalan hingga finishing.'],
            ['code'=>'TKJ','name'=>'Teknik Komputer & Jaringan','icon'=>'ti-network','color'=>'sky','desc'=>'Jaringan fiber optic, server, cloud & keamanan siber.'],
            ['code'=>'RPL','name'=>'Rekayasa Perangkat Lunak','icon'=>'ti-code','color'=>'violet','desc'=>'Pengembangan web, mobile & desktop dengan stack modern.'],
            ['code'=>'MM','name'=>'Multimedia / DKV','icon'=>'ti-photo','color'=>'pink','desc'=>'Desain grafis, video, animasi & broadcasting kreatif.'],
            ['code'=>'MKA','name'=>'Mekatronika','icon'=>'ti-robot','color'=>'orange','desc'=>'Integrasi mekanik, elektronika & informatika untuk robotika.'],
        ];
    }

    private function newsData()
    {
        return [
            ['title'=>'SMKN 1 Katapang Raih Akreditasi A & ISO 9001:2008','date'=>'31 Des 2018','cat'=>'Prestasi','img'=>'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600','excerpt'=>'Komitmen mutu pendidikan vokasi diakui melalui akreditasi A dan sertifikasi manajemen mutu.'],
            ['title'=>'PPDB 2025/2026 Dibuka: 9 Kompetensi Keahlian','date'=>'15 Jun 2025','cat'=>'PPDB','img'=>'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600','excerpt'=>'Pendaftaran online untuk 17 rombel. Jurusan favorit TKRO, TKJ, RPL & Multimedia.'],
            ['title'=>'Kerja Sama Industri: PKL di PT Pindad & PT LEN','date'=>'10 Mei 2025','cat'=>'Kerjasama','img'=>'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600','excerpt'=>'Siswa magang industri untuk penguatan kompetensi link & match dunia kerja.'],
        ];
    }
}
