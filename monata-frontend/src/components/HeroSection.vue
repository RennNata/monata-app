<template>
  <section class="hero-wrapper">
    <div class="hero">
      <!-- Sticky note, pojok kiri atas -->
      <div class="decor decor--note">
        <div class="sticky-note">
          <span class="sticky-note__pin"></span>
          {{ stickyNote.text }}
        </div>
        <div class="check-card">
          <span class="check-card__icon">✓</span>
        </div>
      </div>

      <!-- Kartu pengingat, pojok kanan atas -->
      <div class="decor decor--reminder">
        <div class="clock-card">🕐</div>
        <div class="reminder-card">
          <p class="reminder-card__label">Pengingat</p>
          <p class="reminder-card__title">{{ reminder.title }}</p>
          <p class="reminder-card__desc">{{ reminder.description }}</p>
          <span class="reminder-card__time">🕐 {{ reminder.time }}</span>
        </div>
      </div>

      <!-- Konten utama di tengah -->
      <div class="hero__center">
        <div class="hero__icon">
          <span class="hero__icon-dot hero__icon-dot--blue"></span>
          <span class="hero__icon-dot"></span>
          <span class="hero__icon-dot"></span>
          <span class="hero__icon-dot"></span>
        </div>
        <h1 class="hero__title">
        TATA USAHA<br />
          <span class="hero__title--muted">BELI PERLENGKAPAN SEKOLAHMU SEKARANG JUGA</span>
        </h1>
        <button type="button" class="hero__cta">MASUK</button>
      </div>

      <!-- Kartu tugas hari ini, pojok kiri bawah -->
      <div class="decor decor--tasks">
        <div class="tasks-card">
          <p class="tasks-card__title">Tugas Hari Ini</p>
          <div v-for="task in todayTasks" :key="task.id" class="tasks-card__item">
            <span class="tasks-card__badge" :style="{ background: task.color }"></span>
            <div class="tasks-card__info">
              <p class="tasks-card__name">{{ task.title }}</p>
              <p class="tasks-card__date">{{ task.date }}</p>
              <div class="tasks-card__progress">
                <div class="tasks-card__bar" :style="{ width: task.progress + '%' }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="decor decor--integrations">
        <div class="integrations-card">
          <p class="integrations-card__title">Terhubung Dengan</p>
          <div class="integrations-card__icons">
            <span
              v-for="item in integrations"
              :key="item.id"
              class="integrations-card__icon"
              :style="{ background: item.color }"
              :title="item.name"
            >
              {{ item.name.charAt(0) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// Semua data di bawah ini masih dummy (data palsu).
// Kalau backend udah siap, tinggal ganti import ini jadi hasil fetch API,
// template di atas gak perlu diubah sama sekali.
import { todayTasks, reminder, integrations, stickyNote } from '../data/dummyData.js'
</script>

<style scoped>
.hero-wrapper {
  padding: 0; /* Diubah agar full layar */
  max-width: 100%; /* Diubah agar full layar */
  margin: 0;
}

.hero {
  position: relative;
  background-color: var(--color-bg-panel);
  background-image: radial-gradient(var(--color-dot) 1px, transparent 1px);
  background-size: 18px 18px;
  border-radius: 0; /* Dihilangkan lengkungannya */
  min-height: 90vh; /* Direntangkan tingginya */
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 60px 24px;
}

.hero__center {
  text-align: center;
  max-width: 640px;
  z-index: 2;
}

.hero__icon {
  width: 56px;
  height: 56px;
  background: #fff;
  border-radius: 14px;
  box-shadow: var(--shadow-card);
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 6px;
  align-items: center;
  justify-items: center;
  margin: 0 auto 28px;
  padding: 12px;
}

.hero__icon-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--color-dark);
}

.hero__icon-dot--blue {
  background: var(--color-primary);
}

.hero__title {
  font-size: 48px;
  font-weight: 700;
  line-height: 1.15;
  margin-bottom: 20px;
}

.hero__title--muted {
  color: #b0b4bb;
}

.hero__subtitle {
  font-size: 16px;
  color: #4b5563;
  margin-bottom: 28px;
}

.hero__cta {
  background: var(--color-primary);
  color: #fff;
  border: none;
  padding: 14px 28px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
}

.hero__cta:hover {
  background: var(--color-primary-dark);
}

/* Elemen dekoratif di sekeliling hero */
.decor {
  position: absolute;
  z-index: 1;
}

.decor--note {
  top: 60px;
  left: 60px;
}

.decor--reminder {
  top: 50px;
  right: 60px;
}

.decor--tasks {
  bottom: 40px;
  left: 60px;
}

.decor--integrations {
  bottom: 60px;
  right: 60px;
}

.sticky-note {
  position: relative;
  width: 180px;
  background: var(--color-yellow);
  padding: 20px 16px;
  border-radius: 4px;
  font-size: 13px;
  transform: rotate(-6deg);
  box-shadow: var(--shadow-card);
}

.sticky-note__pin {
  position: absolute;
  top: -6px;
  left: 50%;
  transform: translateX(-50%);
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #ef4444;
}

.check-card {
  position: absolute;
  bottom: -24px;
  right: -20px;
  width: 56px;
  height: 56px;
  background: #fff;
  border-radius: 14px;
  box-shadow: var(--shadow-card);
  display: flex;
  align-items: center;
  justify-content: center;
  transform: rotate(-4deg);
}

.check-card__icon {
  width: 32px;
  height: 32px;
  background: var(--color-primary);
  color: #fff;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
}

.clock-card {
  position: absolute;
  top: -20px;
  left: -20px;
  width: 48px;
  height: 48px;
  background: #fff;
  border-radius: 50%;
  box-shadow: var(--shadow-card);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  transform: rotate(-8deg);
}

.reminder-card {
  width: 200px;
  background: #fff;
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-card);
  padding: 16px;
  transform: rotate(4deg);
}

.reminder-card__label {
  font-size: 11px;
  color: #9ca3af;
  margin-bottom: 4px;
}

.reminder-card__title {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 2px;
}

.reminder-card__desc {
  font-size: 12px;
  color: #6b7280;
  margin-bottom: 10px;
}

.reminder-card__time {
  display: inline-block;
  font-size: 11px;
  background: #eff6ff;
  color: var(--color-primary);
  padding: 4px 8px;
  border-radius: 6px;
}

.tasks-card {
  width: 220px;
  background: #fff;
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-card);
  padding: 16px;
}

.tasks-card__title {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 14px;
}

.tasks-card__item {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  margin-bottom: 14px;
}

.tasks-card__item:last-child {
  margin-bottom: 0;
}

.tasks-card__badge {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
  margin-top: 4px;
}

.tasks-card__info {
  flex: 1;
}

.tasks-card__name {
  font-size: 12px;
  font-weight: 500;
}

.tasks-card__date {
  font-size: 10px;
  color: #9ca3af;
  margin-bottom: 6px;
}

.tasks-card__progress {
  width: 100%;
  height: 4px;
  background: #e5e7eb;
  border-radius: 2px;
}

.tasks-card__bar {
  height: 100%;
  background: var(--color-primary);
  border-radius: 2px;
}

.integrations-card {
  width: 200px;
  background: #fff;
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-card);
  padding: 16px;
}

.integrations-card__title {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 12px;
}

.integrations-card__icons {
  display: flex;
  gap: 8px;
}

.integrations-card__icon {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  color: #fff;
  font-weight: 700;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Sembunyikan elemen dekoratif di layar kecil biar konten inti tetap rapi */
@media (max-width: 900px) {
  .decor {
    display: none;
  }

  .hero {
    min-height: auto;
    padding: 48px 20px;
  }

  .hero__title {
    font-size: 32px;
  }
}
</style>