<template>
  <div class="split-auth">
    <!-- Sisi Kiri: Form Register -->
    <div class="form-section">
      <div class="form-wrapper">
        <div class="brand">
          <span class="dot blue"></span>
          <span class="dot dark"></span>
          <span class="brand-name">Monata</span>
        </div>

        <div class="header-text">
          <h2>Create Account</h2>
          <p>Daftarkan akun siswa Anda ke Monata.</p>
        </div>

        <form @submit.prevent="handleRegister">
          <div class="input-group">
            <label>Nama Lengkap</label>
            <input
              type="text"
              v-model="form.name"
              required
              placeholder="Contoh: Budi Santoso"
            />
          </div>

          <div class="input-group">
            <label>NIS / NIP</label>
            <input
              type="text"
              v-model="form.nis_nip"
              required
              placeholder="Masukkan NIS atau NIP..."
            />
          </div>

          <div class="input-group">
            <label>Password (Min. 8 karakter)</label>
            <input
              type="password"
              v-model="form.password"
              required
              placeholder="••••••••"
            />
          </div>

          <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

          <p v-if="successMessage" class="success-text">{{ successMessage }}</p>
          <button type="submit" class="btn-signin" :disabled="isLoading">
            {{ isLoading ? 'Signing Up...' : 'Sign Up' }}
          </button>
        </form>

        <p class="signup-redirect">
          Sudah punya akun? <router-link to="/login">Sign in</router-link>
        </p>
      </div>
    </div>

    <!-- Sisi Kanan: Banner / Visual Panel Gelap -->
    <div class="banner-section">
      <div class="banner-content">
        <div class="logo-box">M</div>
        <h3>Join Monata Today</h3>
        <p>
          Sistem layanan Tata Usaha SMK Assalaam Bandung, mempermudah pemesanan dan pembelian perlengkapan sekolah.
        </p>

        <!-- Floating Card di dalam Banner -->
        <div class="floating-card">
          <h4>Mulai </h4>
          <p>Transaksi perlengkapan sekolah lebih terstruktur.</p>
          <div class="card-footer">
            <div class="avatar-group">
              <span class="av">🚀</span>
              <span class="av">📊</span>
              <span class="av">+5</span>
            </div>
            <span class="badge-active">Ready to Use</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../../services/api";

const router = useRouter();
const form = ref({
  name: "",
  nis_nip: "",
  password: "",
});
const errorMessage = ref("");

const isLoading = ref(false);
const successMessage = ref("");

const handleRegister = async () => {
  try {
    errorMessage.value = "";
    isLoading.value = true;
    const response = await api.post("/register", form.value);
    successMessage.value = response.data.message;
    router.push("/login"); // Lempar ke halaman login kalau sukses
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message || "Terjadi kesalahan saat registrasi.";
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.success-text {
  font-size: 13px;
  color: #22c55e;
}
.btn-signin:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
.split-auth {
  display: flex;
  min-height: 100vh;
  font-family: "Inter", sans-serif;
  background: #ffffff;
}

/* Sisi Kiri */
.form-section {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px;
}
.form-wrapper {
  width: 100%;
  max-width: 400px;
}
.brand {
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 700;
  font-size: 18px;
  margin-bottom: 36px;
}
.dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}
.dot.blue {
  background: #3b82f6;
}
.dot.dark {
  background: #0f172a;
}

.header-text h2 {
  font-size: 28px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
}
.header-text p {
  font-size: 14px;
  color: #64748b;
  margin-bottom: 28px;
}

.input-group {
  margin-bottom: 20px;
  display: flex;
  flex-direction: column;
}
.input-group label {
  font-size: 13px;
  font-weight: 600;
  color: #334155;
  margin-bottom: 8px;
}
.input-group input {
  padding: 12px 16px;
  border: 1px solid #cbd5e1;
  border-radius: 12px;
  font-size: 14px;
  outline: none;
  transition: 0.2s;
}
.input-group input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.error-text {
  color: #ef4444;
  font-size: 13px;
  margin-bottom: 16px;
}

.btn-signin {
  width: 100%;
  background: #0f172a;
  color: white;
  border: none;
  padding: 14px;
  border-radius: 12px;
  font-weight: 600;
  font-size: 15px;
  cursor: pointer;
  transition: 0.2s;
}
.btn-signin:hover {
  background: #1e293b;
}

.signup-redirect {
  text-align: center;
  font-size: 13px;
  color: #64748b;
  margin-top: 24px;
}
.signup-redirect a {
  color: #3b82f6;
  text-decoration: none;
  font-weight: 600;
}

/* Sisi Kanan (Banner Gelap) */
.banner-section {
  flex: 1;
  background: #090d16;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px;
  position: relative;
  overflow: hidden;
}
.banner-content {
  max-width: 440px;
  color: white;
  z-index: 2;
}
.logo-box {
  width: 48px;
  height: 48px;
  background: #1e293b;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 20px;
  margin-bottom: 24px;
  border: 1px solid #334155;
}
.banner-content h3 {
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 12px;
}
.banner-content p {
  font-size: 14px;
  color: #94a3b8;
  line-height: 1.6;
  margin-bottom: 32px;
}

/* Floating Card */
.floating-card {
  background: #1e293b;
  border: 1px solid #334155;
  padding: 24px;
  border-radius: 20px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}
.floating-card h4 {
  font-size: 15px;
  font-weight: 600;
  margin-bottom: 8px;
  color: #f8fafc;
}
.floating-card p {
  font-size: 12px;
  color: #94a3b8;
  margin-bottom: 16px;
}
.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.avatar-group {
  display: flex;
  gap: -4px;
}
.av {
  background: #334155;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  border: 2px solid #1e293b;
}
.badge-active {
  background: rgba(59, 130, 246, 0.1);
  color: #3b82f6;
  font-size: 11px;
  padding: 4px 10px;
  border-radius: 20px;
  font-weight: 600;
}

/* Responsif untuk layar kecil */
@media (max-width: 900px) {
  .banner-section {
    display: none;
  }
}
</style>
