<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Navbar from '../../components/Navbar.vue'

const route = useRoute()
const router = useRouter()

// Data Produk dari Query Katalog
const product = ref({
  id: route.query.productId || '',
  name: route.query.productName || 'Produk Tidak Dipilih',
  price: Number(route.query.productPrice) || 0,
  icon: route.query.productIcon || '📦',
  stock: Number(route.query.productStock) || 0
})

// Input Form
const quantity = ref(1)
const notes = ref('')
const isLoading = ref(false)
const errorMessage = ref('')

// Hitung Total Harga Otomatis
const totalPrice = computed(() => {
  return product.value.price * quantity.value
})

// Kirim Pesanan ke Backend
const submitOrder = async () => {
  if (quantity.value < 1) {
    alert('Jumlah pesanan minimal 1 ya!')
    return
  }

  try {
    isLoading.value = true
    errorMessage.value = ''
    const token = localStorage.getItem('token')

    if (!token) {
      alert('Lu harus login dulu bro!')
      router.push('/login')
      return
    }

    // UPDATE PAYLOAD DI SINIsesuaiin sama yang diminta Laravel
    await axios.post(
      'http://localhost:8000/api/transactions', // Sesuaikan URL backend lu / ngrok
      {
        total_harga: totalPrice.value,
        transaction_details: [
          {
            id_produk: product.value.id,
            jumlah: quantity.value,
            harga: product.value.price
          }
        ]
      },
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )

    alert('Pesanan berhasil dibuat!')
    router.push('/profile')
  } catch (error) {
    console.error('Gagal bikin pesanan:', error)
    errorMessage.value = error.response?.data?.message || 'Gagal memproses pesanan.'
  } finally {
    isLoading.value = false
  }
}

const formatRupiah = (number) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number)
}

onMounted(() => {
  // Kalau masuk tanpa milih produk dari katalog, balikin ke katalog
  if (!product.value.id) {
    alert('Pilih produk dulu dari katalog!')
    router.push('/katalog')
  }
})
</script>

<template>
  <div class="order-page">
    <Navbar />

    <main class="container">
      <div class="order-card">
        <button class="btn-back" @click="router.push('/katalog')">← Kembali ke Katalog</button>

        <h2>Buat Pesanan Baru</h2>
        <p class="subtitle">Periksa detail produk dan tentukan jumlah yang ingin dipesan.</p>

        <div v-if="errorMessage" class="error-box">
          ⚠️ {{ errorMessage }}
        </div>

        <form @submit.prevent="submitOrder">
          <!-- Detail Produk Ringkas -->
          <div class="product-summary">
            <span class="product-icon">{{ product.icon }}</span>
            <div class="product-details">
              <h3>{{ product.name }}</h3>
              <p class="price">{{ formatRupiah(product.price) }} / pcs</p>
              <span class="stock-tag">Sisa Stok: {{ product.stock }}</span>
            </div>
          </div>

          <!-- Input Jumlah -->
          <div class="form-group">
            <label for="quantity">Jumlah Pesanan</label>
            <div class="qty-input">
              <button type="button" @click="quantity > 1 && quantity--">-</button>
              <input 
                id="quantity" 
                v-model.number="quantity" 
                type="number" 
                min="1" 
                :max="product.stock || 99"
                required 
              />
              <button type="button" @click="quantity < (product.stock || 99) && quantity++">+</button>
            </div>
          </div>

          <!-- Input Catatan Opsional -->
          <!-- <div class="form-group">
            <label for="notes">Catatan Tambahan (Opsional)</label>
            <textarea 
              id="notes" 
              v-model="notes" 
              placeholder="Contoh: Ukuran L, warna hitam, dll." 
              rows="3"
            ></textarea>
          </div> -->

          <!-- Total & Submit Button -->
          <div class="total-section">
            <div class="total-info">
              <span>Total Pembayaran</span>
              <strong>{{ formatRupiah(totalPrice) }}</strong>
            </div>

            <button type="submit" class="btn-submit" :disabled="isLoading">
              {{ isLoading ? 'Memproses Pesanan...' : 'Konfirmasi Pesanan' }}
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<style scoped>
.order-page {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background-color: #0f172a;
  color: #f8fafc;
  min-height: 100vh;
}

.container {
  max-width: 650px;
  margin: 0 auto;
  padding: 40px 20px;
}

.order-card {
  background-color: #1e293b;
  border: 1px solid #334155;
  border-radius: 16px;
  padding: 32px;
}

.btn-back {
  background: none;
  border: none;
  color: #38bdf8;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
  margin-bottom: 20px;
}

.order-card h2 {
  margin: 0 0 4px 0;
  font-size: 1.8rem;
}

.subtitle {
  color: #94a3b8;
  margin: 0 0 24px 0;
  font-size: 14px;
}

.error-box {
  background-color: rgba(239, 68, 68, 0.15);
  border: 1px solid #ef4444;
  color: #f87171;
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 14px;
}

/* Product Summary */
.product-summary {
  display: flex;
  align-items: center;
  gap: 16px;
  background-color: #0f172a;
  border: 1px solid #334155;
  padding: 16px 20px;
  border-radius: 12px;
  margin-bottom: 24px;
}

.product-icon {
  font-size: 2.5rem;
}

.product-details h3 {
  margin: 0 0 4px 0;
  font-size: 1.1rem;
}

.product-details .price {
  margin: 0 0 6px 0;
  color: #38bdf8;
  font-weight: 700;
}

.stock-tag {
  font-size: 12px;
  color: #94a3b8;
  background-color: rgba(148, 163, 184, 0.1);
  padding: 2px 8px;
  border-radius: 4px;
}

/* Form Styles */
.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 8px;
  font-size: 14px;
  color: #f8fafc;
}

.qty-input {
  display: flex;
  align-items: center;
  gap: 8px;
  max-width: 160px;
}

.qty-input button {
  width: 38px;
  height: 38px;
  background-color: #0f172a;
  border: 1px solid #334155;
  color: #f8fafc;
  border-radius: 8px;
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
}

.qty-input input {
  width: 60px;
  height: 38px;
  text-align: center;
  background-color: #0f172a;
  border: 1px solid #334155;
  color: #f8fafc;
  border-radius: 8px;
  font-weight: 700;
  font-size: 16px;
}

textarea {
  width: 100%;
  background-color: #0f172a;
  border: 1px solid #334155;
  color: #f8fafc;
  padding: 12px;
  border-radius: 8px;
  font-family: inherit;
  outline: none;
  resize: vertical;
}

textarea:focus {
  border-color: #38bdf8;
}

/* Total Section */
.total-section {
  border-top: 1px solid #334155;
  padding-top: 20px;
  margin-top: 28px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.total-info span {
  display: block;
  font-size: 13px;
  color: #94a3b8;
}

.total-info strong {
  font-size: 1.4rem;
  color: #38bdf8;
}

.btn-submit {
  background-color: #38bdf8;
  color: #0f172a;
  border: none;
  padding: 12px 28px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-submit:hover:not(:disabled) {
  background-color: #7dd3fc;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>