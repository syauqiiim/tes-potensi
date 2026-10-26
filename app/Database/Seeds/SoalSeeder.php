<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SoalSeeder extends Seeder
{
    public function run()
    {
        // [kategori, soal, A, B, C, kunci]
        $data = [
            // ---- Numerik (1-17) ----
            ['numerik', '15 + 27 = ...', '41', '42', '43', 'B'],
            ['numerik', '144 : 12 = ...', '11', '12', '13', 'B'],
            ['numerik', '25% dari 80 adalah ...', '15', '20', '25', 'B'],
            ['numerik', 'Lanjutkan deret: 2, 4, 8, 16, ...', '24', '30', '32', 'C'],
            ['numerik', 'Lanjutkan deret: 3, 6, 9, 12, ...', '14', '15', '16', 'B'],
            ['numerik', '7 x 8 = ...', '54', '56', '58', 'B'],
            ['numerik', 'Jika x + 9 = 20, maka x = ...', '9', '10', '11', 'C'],
            ['numerik', 'Lanjutkan deret: 1, 4, 9, 16, ...', '20', '24', '25', 'C'],
            ['numerik', '3/4 dari 100 adalah ...', '65', '70', '75', 'C'],
            ['numerik', 'Sebuah mobil melaju 60 km/jam selama 2,5 jam. Jarak yang ditempuh adalah ... km', '120', '150', '180', 'B'],
            ['numerik', '0,5 x 40 = ...', '10', '20', '25', 'B'],
            ['numerik', 'Lanjutkan deret: 100, 90, 80, 70, ...', '50', '60', '65', 'B'],
            ['numerik', 'Rata-rata dari 4, 6, 8, 10, 12 adalah ...', '7', '8', '9', 'B'],
            ['numerik', '2 pangkat 5 = ...', '16', '32', '64', 'B'],
            ['numerik', 'Harga 5 buku Rp45.000. Harga 1 buku adalah ...', 'Rp8.000', 'Rp9.000', 'Rp10.000', 'B'],
            ['numerik', '1.000 - 357 = ...', '633', '643', '653', 'B'],
            ['numerik', 'Lanjutkan deret: 5, 10, 20, 40, ...', '60', '70', '80', 'C'],

            // ---- Verbal (18-34) ----
            ['verbal', 'Sinonim kata PANDAI adalah ...', 'Bodoh', 'Cerdas', 'Malas', 'B'],
            ['verbal', 'Antonim kata RAJIN adalah ...', 'Giat', 'Tekun', 'Malas', 'C'],
            ['verbal', 'Sinonim kata INDAH adalah ...', 'Elok', 'Buruk', 'Kotor', 'A'],
            ['verbal', 'Antonim kata TINGGI adalah ...', 'Pendek', 'Jangkung', 'Besar', 'A'],
            ['verbal', 'Dokter : Rumah sakit = Guru : ...', 'Murid', 'Sekolah', 'Buku', 'B'],
            ['verbal', 'Panas : Dingin = Terang : ...', 'Gelap', 'Cahaya', 'Lampu', 'A'],
            ['verbal', 'Sinonim kata CEPAT adalah ...', 'Lambat', 'Lekas', 'Pelan', 'B'],
            ['verbal', 'Antonim kata KAYA adalah ...', 'Miskin', 'Makmur', 'Kuat', 'A'],
            ['verbal', 'Ikan : Air = Burung : ...', 'Sarang', 'Udara', 'Pohon', 'B'],
            ['verbal', 'Penulisan kata baku yang benar adalah ...', 'Apotik', 'Apotek', 'Apotehk', 'B'],
            ['verbal', 'Sinonim kata BAHAGIA adalah ...', 'Sedih', 'Gembira', 'Marah', 'B'],
            ['verbal', 'Antonim kata AWAL adalah ...', 'Mula', 'Akhir', 'Tengah', 'B'],
            ['verbal', 'Penulisan kata baku yang benar adalah ...', 'Analisa', 'Analisis', 'Analisys', 'B'],
            ['verbal', 'Pena : Menulis = Pisau : ...', 'Memotong', 'Dapur', 'Tajam', 'A'],
            ['verbal', 'Sinonim kata BESAR adalah ...', 'Kecil', 'Raya', 'Sempit', 'B'],
            ['verbal', 'Antonim kata MAJU adalah ...', 'Mundur', 'Cepat', 'Jalan', 'A'],
            ['verbal', 'Penulisan kata baku yang benar adalah ...', 'Ijin', 'Izin', 'Idzin', 'B'],

            // ---- Logika (35-50) ----
            ['logika', 'Semua kucing adalah hewan. Tom adalah kucing. Maka ...', 'Tom bukan hewan', 'Tom adalah hewan', 'Tom bukan kucing', 'B'],
            ['logika', 'Jika hujan, maka jalan basah. Jalan tidak basah. Maka ...', 'Hujan', 'Tidak hujan', 'Mungkin hujan', 'B'],
            ['logika', 'Andi lebih tinggi dari Budi, Budi lebih tinggi dari Citra. Siapa yang paling tinggi?', 'Andi', 'Budi', 'Citra', 'A'],
            ['logika', 'Semua A adalah B. Sebagian B adalah C. Kesimpulan yang PASTI benar adalah ...', 'Semua A adalah C', 'Sebagian A adalah C', 'Belum tentu ada A yang merupakan C', 'C'],
            ['logika', 'Lanjutkan deret huruf: A, C, E, G, ...', 'H', 'I', 'J', 'B'],
            ['logika', 'Lanjutkan deret huruf: B, D, F, H, ...', 'I', 'J', 'K', 'B'],
            ['logika', 'Hari ini hari Rabu. Tiga hari lagi adalah hari ...', 'Jumat', 'Sabtu', 'Minggu', 'B'],
            ['logika', 'Jika 5 mesin membuat 5 barang dalam 5 menit, maka 100 mesin membuat 100 barang dalam ...', '5 menit', '20 menit', '100 menit', 'A'],
            ['logika', 'Rina adalah saudara perempuan ayah Dodi. Bagi Dodi, Rina adalah ...', 'Bibi', 'Nenek', 'Kakak', 'A'],
            ['logika', 'Semua pegawai wajib berseragam. Dewi adalah pegawai. Maka ...', 'Dewi wajib berseragam', 'Dewi tidak wajib berseragam', 'Dewi bukan pegawai', 'A'],
            ['logika', 'Raka finis sebelum Sari, dan Sari finis sebelum Tono. Siapa yang finis paling akhir?', 'Raka', 'Sari', 'Tono', 'C'],
            ['logika', 'Jam menunjukkan pukul 03.00. Sudut yang dibentuk jarum jam dan jarum menit adalah ...', '60 derajat', '90 derajat', '120 derajat', 'B'],
            ['logika', 'Semua P adalah Q, dan tidak ada Q yang R. Maka ...', 'Sebagian P adalah R', 'Tidak ada P yang R', 'Semua R adalah P', 'B'],
            ['logika', 'Pola: persegi, segitiga, lingkaran, persegi, segitiga, ... Bentuk berikutnya adalah ...', 'Persegi', 'Lingkaran', 'Segitiga', 'B'],
            ['logika', 'Seseorang menghadap utara, lalu berputar 90 derajat ke kanan. Sekarang ia menghadap ...', 'Barat', 'Timur', 'Selatan', 'B'],
            ['logika', 'Dalam satu keluarga terdapat 2 ayah dan 2 anak. Jumlah orang paling sedikit adalah ...', '3', '4', '2', 'A'],
        ];

        $rows = [];
        foreach ($data as $i => $d) {
            $rows[] = [
                'nomor_urut' => $i + 1,
                'kategori'   => $d[0],
                'teks_soal'  => $d[1],
                'opsi_a'     => $d[2],
                'opsi_b'     => $d[3],
                'opsi_c'     => $d[4],
                'kunci'      => $d[5],
                'aktif'      => 1,
            ];
        }

        $this->db->table('soal')->insertBatch($rows);
    }
}
