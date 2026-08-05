<?php

namespace App\Http\Controllers;

class KpiCoreValueController extends Controller
{
    // Halaman referensi: Definisi Core Value IKHLAS
    public function index()
    {
        $coreValues = [
            'integritas' => [
                'label' => 'Integritas',
                'points' => [
                    'Kesesuaian antara perkataan dan perbuatan',
                    'Mampu bertindak dan berucap jujur',
                    'Bekerja dengan baik meskipun tanpa diawasi',
                    'Kepatuhan terhadap kode etik dan kebijakan penggunaan aset perusahaan',
                    'Tingkat validitas dan transparansi data yang disajikan',
                ],
            ],
            'kekeluargaan' => [
                'label' => 'Kekeluargaan',
                'points' => [
                    'Memiliki Empati',
                    'Kompak/Solid/Saling support aktif',
                    'Menciptakan suasana kerja yang kondusif/sehat',
                    'Inisiatif mentoring atau bantuan lintas departemen yang tercatat',
                    'Tingkat partisipasi dalam kegiatan tim dan perusahaan',
                ],
            ],
            'handal' => [
                'label' => 'Handal',
                'points' => [
                    'Mengerjakan tugas yang diberikan sampai tuntas dengan baik',
                    'Senang menerima tantangan',
                    'Solutif/kompeten/terampil',
                    'Konsisten',
                    'Jumlah ide perbaikan proses yang diusulkan dan diimplementasikan',
                    'Persentase on-time delivery untuk laporan atau penyelesaian tugas',
                ],
            ],
            'loyalitas' => [
                'label' => 'Loyalitas',
                'points' => [
                    'Positive person (konteks: Perubahan)',
                    'Mampu menjaga nama baik perusahaan',
                    'Memberikan feedback yang membangun perusahaan',
                    'Berpartisipasi dalam kegiatan/acara perusahaan',
                    'Kepatuhan terhadap kebijakan perusahaan saat terjadi konflik kepentingan',
                    'Kejadian pelanggaran kerahasiaan data',
                ],
            ],
            'amanah' => [
                'label' => 'Amanah',
                'points' => [
                    'Ketulusan dalam bekerja',
                    'Dapat dipercaya mengerjakan/menjalankan tugas (terkait hal yang sensitif)',
                    'Bertanggung jawab',
                    'Tingkat kerusakan/kehilangan aset yang berada di bawah tanggung jawab individu/tim',
                    'Tingkat penyelesaian tugas (Task Completion Rate) tanpa reminder dari atasan',
                    'Tingkat kepatuhan dalam pelaporan dan reimbursement dana operasional',
                ],
            ],
            'saling_menghargai' => [
                'label' => 'Saling Menghargai',
                'points' => [
                    'Memiliki Etika/adab yang baik',
                    'Memberikan feedback yang membangun ke sesama rekan kerja',
                    'Menghormati perbedaan (SARA)',
                    'Menjauhi perilaku negatif seperti gosip/kritik yang berlebihan',
                    'Memiliki habit komunikasi yang baik (Tolong, Maaf, Terima Kasih) / Frekuensi ucapan terima kasih/apresiasi kepada tim support dan non-manajerial',
                    'Turnover ide perbaikan yang diajukan oleh karyawan junior/non-manajerial',
                ],
            ],
        ];

        return view('kpi.core-value-definition', compact('coreValues'));
    }
}