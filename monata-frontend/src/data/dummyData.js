// Dummy data buat homepage MONATA
// Nanti kalau backend temenmu udah siap, tinggal ganti isi file ini
// jadi hasil fetch dari API — komponen yang pakai data ini gak perlu diubah.

export const todayTasks = [
  {
    id: 1,
    title: 'Rekap Surat Masuk',
    date: '3 Sep',
    progress: 60,
    color: '#F59E0B'
  },
  {
    id: 2,
    title: 'Input Data Siswa Baru',
    date: '5 Sep',
    progress: 85,
    color: '#22C55E'
  },
  {
    id: 3,
    title: 'Arsip Laporan Keuangan',
    date: '8 Sep',
    progress: 30,
    color: '#EF4444'
  }
]

export const reminder = {
  title: 'buka jam',
//   description: 'lebih dari 10 surat masuk yang belum diarsipkan',
  time: '07:00 - 15:00'
}

export const integrations = [
  { id: 1, name: 'Google Drive', color: '#4285F4' },
  { id: 2, name: 'WhatsApp', color: '#25D366' },
  { id: 3, name: 'Google Calendar', color: '#EA4335' }
]

export const stickyNote = {
  text: 'Jangan lupa update laporan bulanan sebelum tanggal 5!'
}