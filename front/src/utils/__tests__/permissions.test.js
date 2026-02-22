import { describe, it, expect, beforeEach } from 'vitest'
import { usePermissions } from '../permissions.js'

describe('Permissions Utility', () => {
  beforeEach(() => {
    localStorage.clear()
  })

  it('setPermissions saves to localStorage', () => {
    const permissions = ['users.index', 'users.store', 'goals.index']
    usePermissions.setPermissions(permissions)

    const stored = JSON.parse(localStorage.getItem('permissions'))
    expect(stored).toEqual(permissions)
  })

  it('getPermissions retrieves from localStorage', async () => {
    const permissions = ['users.index', 'goals.index']
    localStorage.setItem('permissions', JSON.stringify(permissions))

    const result = await usePermissions.getPermissions()
    expect(result).toEqual(permissions)
  })

  it('getPermissions returns empty array when nothing stored', async () => {
    const result = await usePermissions.getPermissions()
    expect(result).toEqual([])
  })

  it('hasPermission returns true for existing permission', async () => {
    usePermissions.setPermissions(['users.index', 'users.store', 'goals.index'])

    const result = await usePermissions.hasPermission('users.index')
    expect(result).toBe(true)
  })

  it('hasPermission returns false for non-existing permission', async () => {
    usePermissions.setPermissions(['users.index', 'users.store'])

    const result = await usePermissions.hasPermission('admin.delete')
    expect(result).toBe(false)
  })

  it('hasPermission supports array of permissions', async () => {
    usePermissions.setPermissions(['users.index', 'goals.index'])

    const hasAny = await usePermissions.hasPermission(['users.index', 'admin.panel'])
    expect(hasAny).toBe(true)

    const hasNone = await usePermissions.hasPermission(['admin.panel', 'admin.delete'])
    expect(hasNone).toBe(false)
  })

  it('clearPermissions removes from localStorage', () => {
    usePermissions.setPermissions(['users.index'])
    expect(localStorage.getItem('permissions')).not.toBeNull()

    usePermissions.clearPermissions()
    expect(localStorage.getItem('permissions')).toBeNull()
  })
})
