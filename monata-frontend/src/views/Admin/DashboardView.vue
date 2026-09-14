<template>
  <div class="admin-layout">
    <Sidebar />

    <div class="main-wrapper">
      <header class="topbar">
        <div class="topbar-left">
          <h1 class="page-title">Admin Control Panel</h1>
        </div>
        <div class="topbar-right">
          <div class="admin-badge">
            <span class="avatar">A</span>
            <div class="info">
              <span class="name">Administrator</span>
              <span class="role">Super Admin</span>
            </div>
          </div>
        </div>
      </header>

      <main class="content-area">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import Sidebar from '../../components/Sidebar.vue'
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f8fafc;
  font-family: 'Inter', sans-serif;
}

/* MAIN CONTENT STYLES */
.main-wrapper {
  margin-left: 260px;
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.topbar {
  height: 70px;
  background-color: #ffffff;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 32px;
}

.page-title {
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.admin-badge {
  display: flex;
  align-items: center;
  gap: 12px;
}

.avatar {
  width: 36px;
  height: 36px;
  background-color: #0f172a;
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 14px;
}

.info {
  display: flex;
  flex-direction: column;
}

.info .name {
  font-size: 13px;
  font-weight: 600;
  color: #0f172a;
}

.info .role {
  font-size: 11px;
  color: #64748b;
}

.content-area {
  padding: 32px;
  flex: 1;
}
</style>