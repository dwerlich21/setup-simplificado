import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'

// Mock dependencies before importing the store
vi.mock('@/http/index.js', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
  },
}))

vi.mock('@/env.js', () => ({
  default: {
    api: 'http://localhost:8000/api/v1/',
  },
}))

vi.mock('@/router/index.js', () => ({
  default: {
    push: vi.fn(),
  },
}))

vi.mock('@/composables/messages.js', () => ({
  notifyError: vi.fn(),
}))

vi.mock('@/utils/permissions.js', () => ({
  usePermissions: {
    setPermissions: vi.fn(),
    getPermissions: vi.fn(),
    hasPermission: vi.fn(),
    clearPermissions: vi.fn(),
  },
}))

import { useAuthStore } from '../auth.js'

describe('Auth Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('loggedIn returns true when currentUser has data', () => {
    const store = useAuthStore()
    store.currentUser = { id: 1, name: 'Test User' }
    expect(store.loggedIn).toBe(true)
  })

  it('loggedIn returns false when currentUser is null', () => {
    const store = useAuthStore()
    store.currentUser = null
    expect(store.loggedIn).toBe(false)
  })

  it('loggedIn returns false when currentUser is empty object', () => {
    const store = useAuthStore()
    store.currentUser = {}
    expect(store.loggedIn).toBe(false)
  })

  it('setCurrentUser updates state', () => {
    const store = useAuthStore()
    const user = { id: 1, name: 'João Silva', email: 'joao@test.com' }
    store.setCurrentUser(user)
    expect(store.currentUser).toEqual(user)
  })

  it('getUser getter returns currentUser', () => {
    const store = useAuthStore()
    const user = { id: 2, name: 'Maria' }
    store.currentUser = user
    expect(store.getUser).toEqual(user)
  })
})
