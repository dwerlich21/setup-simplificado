import { describe, it, expect } from 'vitest'
import {
  toPascalCase,
  toCamelCase,
  toSnakeCase,
  toKebabCase,
  toTitleCase,
  toBoolean,
  toNumber,
  toString,
  deepMerge,
  deepClone,
  removeEmptyProperties,
  truncateText,
  formatBytes,
  setNestedValue,
  arrayToOptions,
  objectToOptions,
} from '../convertVariables.js'

describe('toPascalCase', () => {
  it('converts snake_case', () => {
    expect(toPascalCase('hello_world')).toBe('HelloWorld')
  })

  it('converts camelCase', () => {
    expect(toPascalCase('helloWorld')).toBe('HelloWorld')
  })

  it('returns empty for null/undefined', () => {
    expect(toPascalCase(null)).toBe('')
    expect(toPascalCase(undefined)).toBe('')
    expect(toPascalCase('')).toBe('')
  })
})

describe('toCamelCase', () => {
  it('converts snake_case', () => {
    expect(toCamelCase('hello_world')).toBe('helloWorld')
  })

  it('converts PascalCase', () => {
    expect(toCamelCase('HelloWorld')).toBe('helloWorld')
  })

  it('returns empty for null', () => {
    expect(toCamelCase(null)).toBe('')
  })
})

describe('toSnakeCase', () => {
  it('converts camelCase', () => {
    expect(toSnakeCase('helloWorld')).toBe('hello_world')
  })

  it('converts kebab-case', () => {
    expect(toSnakeCase('hello-world')).toBe('hello_world')
  })

  it('returns empty for null', () => {
    expect(toSnakeCase(null)).toBe('')
  })
})

describe('toKebabCase', () => {
  it('converts camelCase', () => {
    expect(toKebabCase('helloWorld')).toBe('hello-world')
  })

  it('converts snake_case', () => {
    expect(toKebabCase('hello_world')).toBe('hello-world')
  })

  it('returns empty for null', () => {
    expect(toKebabCase(null)).toBe('')
  })
})

describe('toTitleCase', () => {
  it('converts snake_case', () => {
    expect(toTitleCase('hello_world')).toBe('Hello World')
  })

  it('converts camelCase', () => {
    expect(toTitleCase('helloWorld')).toBe('Hello World')
  })

  it('returns empty for null', () => {
    expect(toTitleCase(null)).toBe('')
  })
})

describe('toBoolean', () => {
  it('returns boolean values as-is', () => {
    expect(toBoolean(true)).toBe(true)
    expect(toBoolean(false)).toBe(false)
  })

  it('converts string values', () => {
    expect(toBoolean('true')).toBe(true)
    expect(toBoolean('1')).toBe(true)
    expect(toBoolean('yes')).toBe(true)
    expect(toBoolean('false')).toBe(false)
    expect(toBoolean('no')).toBe(false)
  })

  it('converts numbers', () => {
    expect(toBoolean(1)).toBe(true)
    expect(toBoolean(0)).toBe(false)
    expect(toBoolean(-1)).toBe(true)
  })

  it('converts null/undefined', () => {
    expect(toBoolean(null)).toBe(false)
    expect(toBoolean(undefined)).toBe(false)
  })
})

describe('toNumber', () => {
  it('returns numbers as-is', () => {
    expect(toNumber(42)).toBe(42)
    expect(toNumber(3.14)).toBe(3.14)
  })

  it('converts string numbers', () => {
    expect(toNumber('42')).toBe(42)
    expect(toNumber('3.14')).toBe(3.14)
  })

  it('returns default for NaN', () => {
    expect(toNumber('abc')).toBe(0)
    expect(toNumber('abc', 10)).toBe(10)
  })
})

describe('toString', () => {
  it('converts null/undefined to default', () => {
    expect(toString(null)).toBe('')
    expect(toString(undefined)).toBe('')
    expect(toString(null, 'default')).toBe('default')
  })

  it('converts numbers to string', () => {
    expect(toString(42)).toBe('42')
    expect(toString(0)).toBe('0')
  })
})

describe('deepMerge', () => {
  it('merges simple objects', () => {
    const result = deepMerge({ a: 1 }, { b: 2 })
    expect(result).toEqual({ a: 1, b: 2 })
  })

  it('merges nested objects', () => {
    const result = deepMerge(
      { a: { x: 1 } },
      { a: { y: 2 } }
    )
    expect(result).toEqual({ a: { x: 1, y: 2 } })
  })

  it('handles null source', () => {
    const result = deepMerge({ a: 1 }, null)
    expect(result).toEqual({ a: 1 })
  })
})

describe('deepClone', () => {
  it('clones objects', () => {
    const obj = { a: 1, b: { c: 2 } }
    const clone = deepClone(obj)
    expect(clone).toEqual(obj)
    expect(clone).not.toBe(obj)
    expect(clone.b).not.toBe(obj.b)
  })

  it('clones arrays', () => {
    const arr = [1, [2, 3], { a: 4 }]
    const clone = deepClone(arr)
    expect(clone).toEqual(arr)
    expect(clone).not.toBe(arr)
  })

  it('clones dates', () => {
    const date = new Date('2024-01-01')
    const clone = deepClone(date)
    expect(clone.getTime()).toBe(date.getTime())
    expect(clone).not.toBe(date)
  })

  it('returns primitives as-is', () => {
    expect(deepClone(null)).toBe(null)
    expect(deepClone(42)).toBe(42)
    expect(deepClone('hello')).toBe('hello')
  })
})

describe('removeEmptyProperties', () => {
  it('removes null, undefined, and empty string', () => {
    const result = removeEmptyProperties({ a: 1, b: null, c: '', d: undefined })
    expect(result).toEqual({ a: 1 })
  })

  it('removes empty arrays', () => {
    const result = removeEmptyProperties({ a: 1, b: [] })
    expect(result).toEqual({ a: 1 })
  })

  it('deep clean nested objects', () => {
    const result = removeEmptyProperties({ a: 1, b: { c: null, d: '' } }, true)
    expect(result).toEqual({ a: 1 })
  })
})

describe('truncateText', () => {
  it('returns short text unchanged', () => {
    expect(truncateText('Hello', 10)).toBe('Hello')
  })

  it('truncates long text with ellipsis', () => {
    expect(truncateText('Hello World Test', 5)).toBe('Hello...')
  })

  it('returns empty for null/non-string', () => {
    expect(truncateText(null)).toBe('')
    expect(truncateText(undefined)).toBe('')
  })
})

describe('formatBytes', () => {
  it('returns 0 Bytes for zero', () => {
    expect(formatBytes(0)).toBe('0 Bytes')
  })

  it('formats bytes correctly', () => {
    expect(formatBytes(500)).toBe('500 Bytes')
  })

  it('formats KB correctly', () => {
    expect(formatBytes(1024)).toBe('1 KB')
  })

  it('formats MB correctly', () => {
    expect(formatBytes(1048576)).toBe('1 MB')
  })

  it('formats GB correctly', () => {
    expect(formatBytes(1073741824)).toBe('1 GB')
  })
})

describe('setNestedValue', () => {
  it('sets value using dot notation', () => {
    const obj = {}
    setNestedValue(obj, 'a.b.c', 42)
    expect(obj.a.b.c).toBe(42)
  })

  it('returns obj for invalid inputs', () => {
    expect(setNestedValue(null, 'a', 1)).toBe(null)
    expect(setNestedValue({}, '', 1)).toEqual({})
  })
})

describe('arrayToOptions', () => {
  it('converts string array to options', () => {
    const result = arrayToOptions(['red', 'blue'])
    expect(result).toEqual([
      { value: 'red', label: 'red' },
      { value: 'blue', label: 'blue' },
    ])
  })

  it('passes through objects', () => {
    const input = [{ value: 1, label: 'One' }]
    const result = arrayToOptions(input)
    expect(result).toEqual(input)
  })

  it('returns empty array for non-array', () => {
    expect(arrayToOptions(null)).toEqual([])
  })
})

describe('objectToOptions', () => {
  it('converts object to options array', () => {
    const result = objectToOptions({ active: 'Ativo', inactive: 'Inativo' })
    expect(result).toEqual([
      { value: 'active', label: 'Ativo' },
      { value: 'inactive', label: 'Inativo' },
    ])
  })

  it('returns empty for null', () => {
    expect(objectToOptions(null)).toEqual([])
  })
})
