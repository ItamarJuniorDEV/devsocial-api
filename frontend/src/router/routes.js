const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        name: 'Feed',
        component: () => import('pages/FeedPage.vue'),
        meta: { auth: true }
      },
      {
        path: 'profile/edit',
        name: 'ProfileEdit',
        component: () => import('pages/ProfileEditPage.vue'),
        meta: { auth: true }
      },
      {
        path: 'profile/:id(\\d+)',
        name: 'Profile',
        component: () => import('pages/ProfilePage.vue'),
        meta: { auth: true }
      },
      {
        path: 'post/:id(\\d+)',
        name: 'Post',
        component: () => import('pages/PostPage.vue'),
        meta: { auth: true }
      },
      {
        path: 'search',
        name: 'Search',
        component: () => import('pages/SearchPage.vue'),
        meta: { auth: true }
      }
    ]
  },
  {
    path: '/auth',
    component: () => import('layouts/AuthLayout.vue'),
    children: [
      {
        path: 'login',
        name: 'Login',
        component: () => import('pages/LoginPage.vue'),
        meta: { guest: true }
      },
      {
        path: 'register',
        name: 'Register',
        component: () => import('pages/RegisterPage.vue'),
        meta: { guest: true }
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('pages/NotFoundPage.vue')
  }
]

export default routes
