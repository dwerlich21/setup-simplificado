import { describe, it, expect } from 'vitest'
import {
  convertDateText,
  convertDateIso,
  convertHour,
  generateNickname,
  addZeros,
  encodeId,
  timestampToHour,
  generateRandomColors,
} from '../functions.js'

describe('convertDateText', () => {
  it('converts ISO datetime to text format', () => {
    expect(convertDateText('2024-03-15 10:30:00')).toBe('15 Mar, 2024')
  })

  it('converts date with January', () => {
    expect(convertDateText('2024-01-05 08:00:00')).toBe('05 Jan, 2024')
  })

  it('converts date with December', () => {
    expect(convertDateText('2024-12-25 18:00:00')).toBe('25 Dez, 2024')
  })

  it('returns dash for null/undefined', () => {
    expect(convertDateText(null)).toBe('-')
    expect(convertDateText(undefined)).toBe('-')
    expect(convertDateText('')).toBe('-')
  })
})

describe('convertDateIso', () => {
  it('converts DD/MM/YYYY to YYYY-MM-DD', () => {
    expect(convertDateIso('15/03/2024')).toBe('2024-03-15')
  })

  it('converts first of January', () => {
    expect(convertDateIso('01/01/2024')).toBe('2024-01-01')
  })

  it('returns empty for null/empty', () => {
    expect(convertDateIso(null)).toBe('')
    expect(convertDateIso('')).toBe('')
  })
})

describe('convertHour', () => {
  it('extracts hour:minute from datetime', () => {
    expect(convertHour('2024-03-15 10:30:00.000')).toBe('10:30')
  })

  it('extracts hour from another datetime', () => {
    expect(convertHour('2024-01-01 08:15:30.123')).toBe('08:15')
  })
})

describe('generateNickname', () => {
  it('generates nickname from full name', () => {
    expect(generateNickname('João Silva')).toBe('JS')
  })

  it('generates nickname from name with middle names', () => {
    expect(generateNickname('Maria da Silva Santos')).toBe('MS')
  })

  it('generates nickname from single name', () => {
    expect(generateNickname('Pedro')).toBe('PP')
  })
})

describe('addZeros', () => {
  it('pads single digit', () => {
    expect(addZeros(5)).toBe('05')
  })

  it('keeps double digit', () => {
    expect(addZeros(12)).toBe('12')
  })

  it('pads string digit', () => {
    expect(addZeros('3')).toBe('03')
  })
})

describe('encodeId', () => {
  it('encodes number to base64', () => {
    expect(encodeId(1)).toBe(btoa('1'))
  })

  it('encodes larger number', () => {
    expect(encodeId(12345)).toBe(btoa('12345'))
  })
})

describe('timestampToHour', () => {
  it('converts Unix timestamp to HH:MM', () => {
    // Create a known timestamp
    const date = new Date(2024, 0, 1, 14, 30) // 14:30
    const timestamp = Math.floor(date.getTime() / 1000)
    expect(timestampToHour(timestamp)).toBe('14:30')
  })

  it('pads hours and minutes', () => {
    const date = new Date(2024, 0, 1, 8, 5) // 08:05
    const timestamp = Math.floor(date.getTime() / 1000)
    expect(timestampToHour(timestamp)).toBe('08:05')
  })
})

describe('generateRandomColors', () => {
  it('generates correct number of colors', () => {
    const colors = generateRandomColors(5)
    expect(colors).toHaveLength(5)
  })

  it('generates valid hex colors', () => {
    const colors = generateRandomColors(3)
    colors.forEach(color => {
      expect(color).toMatch(/^#[0-9A-F]{6}$/)
    })
  })

  it('throws for invalid count', () => {
    expect(() => generateRandomColors(0)).toThrow()
    expect(() => generateRandomColors(-1)).toThrow()
    expect(() => generateRandomColors('abc')).toThrow()
  })

  it('generates single color', () => {
    const colors = generateRandomColors(1)
    expect(colors).toHaveLength(1)
  })
})
