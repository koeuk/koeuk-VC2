import { createRouter, createWebHistory } from 'vue-router'
import axiosInstance from '@/plugins/axios'
import { useAuthStore } from '@/stores/auth-store'
import { createAcl, defineAclRules } from 'vue-simple-acl'
import LoginForm from '@/Components/loginPage.vue'
import SignUpForm from '@/Components/SignUpPage.vue'
import MessangerComponent from '@/Components/Messanger.vue'
import BookingPlace from '@/Components/BookingForm.vue'

const simpleAcl = createAcl({})

const routes = [
  {
    path: '/admin/dashboard',
    name: 'dashboard',
    component: () => import('../views/Admin/DashboardView.vue'),
    meta: {
      requiresAuth: true,
      role: 'admin'
    }
  },
  {
    path: '/login',
    name: 'login',
    component: LoginForm,
    meta: {
      requiresAuth: false // Public page, no authentication required
    }
  },
  {
    path: '/signup',
    name: 'signup',
    component: SignUpForm,
    meta: {
      requiresAuth: false // Public page, no authentication required
    }
  },
  {
    path: '/messanger',
    name: 'messanger',
    component: MessangerComponent,
    meta: {
      requiresAuth: false // Public page, no authentication required
    }
  },
  {
    path: '/book',
    name: 'book',
    component: BookingPlace,
    meta: {
      requiresAuth: false // Public page, no authentication required
    }
  },
  {
    path: '/',
    name: 'home',
    component: () => import('../views/Web/HomeView.vue'),
    meta: {
      requiresAuth: false // Public page, no authentication required
    }
  },
  
  {
    path: '/profile',
    name: 'profile',
    component: () => import('../Components/Service/ProfileEdit/Profile.vue')
  },
  {
    path: '/profile/edit',
    name: 'profile-edit',
    component: () => import('../Components/Service/ProfileEdit/ProfileEdit.vue')
  },
  {
    path: '/fixer',
    name: 'fixer',
    component: () => import('../Components/FixerVue.vue')
  },
  {
    path: '/HomeFixer',
    name: 'HomeFixer',
    component: () => import('../views/Fixer/HomeFixer.vue'),
    meta: { requiresAuth: true, role: 'fixer' },
    children: [
      {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('../Components/Fixer/DashBoard.vue')
      },
      {
        path: '/booking',
        name: 'Booking',
        component: () => import('../Components/Fixer/ListBooking.vue')
      },
      {
        path: '/skill',
        name: 'Skill',
        component: () => import('../Components/Fixer/CardSkill.vue')
      },
      {
        path: '/chatView',
        name: 'ChatView',
        component: () => import('../Components/Fixer/ChatView.vue')
      },
      {
        path: '/historyView',
        name: 'HistoryView',
        component: () => import('../Components/Fixer/HistoryView.vue')
      }
    ]
  },
  
  {
    path: '/fixerForm',
    name: 'fixerForm',
    component: () => import('../views/Web/Fixer/form.vue')
  },
  {
    path: '/paymentForm',
    name: 'paymentForm',
    component: () => import('../Components/Fixer/Payment.vue')
  },

  {
    path: '/fixerUser',
    name: 'fixerUser',
    component: () => import('@/views/Fixer/List.vue')
  },
  {
    path: '/Skill',
    name: 'Skill-View',
    component: () => import('../Components/Fixer/CardSkill.vue')
  },
  {
    path: '/booking',
    name: 'booking-View',
    component: () => import('../Components/Fixer/ListBooking.vue')
  },
  {
    path: '/HistoryView',
    name: 'HistoryView',
    component: () => import('../Components/Fixer/HistoryView.vue')
  },
  {
    path: '/map',
    name: 'map',
    component: () => import('../Components/Map.vue')
  },
  {
    path: '/chatView',
    name: 'chatView',
    component: () => import('../Components/Fixer/ChatView.vue')
  },
  {
    path: '/dashboard',
    name: 'dash-board',
    component: () => import('../Components/Fixer/DashBoard.vue')
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})


router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'Login' });
  } else if (to.meta.role && authStore.user?.role !== to.meta.role) {
    next({ name: 'Home' }); 
  } else {
    next();
  }
});

export { router, simpleAcl }