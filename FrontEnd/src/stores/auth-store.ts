import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
  const userData = localStorage.getItem('user');
  const user = ref(userData ? JSON.parse(userData) : null);
  const isAuthenticated = ref(!!user.value);
  const permissions = ref([]);
  const roles = ref([]);

  function setAuthUser(userData: any) {
    user.value = userData;
    isAuthenticated.value = true;
    permissions.value = userData.permissions || [];
    roles.value = userData.roles || [];
    localStorage.setItem('user', JSON.stringify(userData));
  }

  function logout() {
    user.value = null;
    isAuthenticated.value = false;
    permissions.value = [];
    roles.value = [];
    localStorage.removeItem('user');
    localStorage.removeItem('access_token');
    localStorage.removeItem('iconify-version');
    localStorage.removeItem('__vue-devtools-theme__');
    localStorage.removeItem('iconify-count');
    localStorage.removeItem('__vue-devtools-frame-state__');
  }

  return { user, isAuthenticated, permissions, roles, setAuthUser, logout };
});
