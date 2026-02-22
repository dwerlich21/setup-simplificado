import { describe, it, expect } from 'vitest'
import { maskPhone, maskDate, maskCpfCnpj } from '../masks.js'

describe('maskPhone', () => {
  it('formats landline (10 digits)', () => {
    expect(maskPhone('1133334444')).toBe('(11) 3333-4444')
  })

  it('formats mobile (11 digits)', () => {
    expect(maskPhone('11999887766')).toBe('(11) 99988-7766')
  })

  it('formats partial input - just DDD', () => {
    expect(maskPhone('11')).toBe('11')
  })

  it('formats partial input - DDD + start', () => {
    expect(maskPhone('119')).toBe('(11) 9')
  })

  it('formats partial input - 7 digits', () => {
    expect(maskPhone('1199988')).toBe('(11) 9998-8')
  })

  it('handles already formatted input', () => {
    const result = maskPhone('(11) 99988-7766')
    expect(result).toBe('(11) 99988-7766')
  })
})

describe('maskDate', () => {
  it('formats complete date', () => {
    expect(maskDate('22022026')).toBe('22/02/2026')
  })

  it('formats partial date - day', () => {
    expect(maskDate('22')).toBe('22')
  })

  it('formats partial date - day/month', () => {
    expect(maskDate('2202')).toBe('22/02')
  })

  it('formats partial date - day/month/partial year', () => {
    expect(maskDate('220220')).toBe('22/02/20')
  })

  it('handles already formatted input', () => {
    const result = maskDate('22/02/2026')
    expect(result).toBe('22/02/2026')
  })
})

describe('maskCpfCnpj', () => {
  it('formats complete CPF (11 digits)', () => {
    expect(maskCpfCnpj('52998224725')).toBe('529.982.247-25')
  })

  it('formats partial CPF - 4 digits', () => {
    expect(maskCpfCnpj('5299')).toBe('529.9')
  })

  it('formats partial CPF - 7 digits', () => {
    expect(maskCpfCnpj('5299822')).toBe('529.982.2')
  })

  it('formats partial CPF - 10 digits', () => {
    expect(maskCpfCnpj('5299822472')).toBe('529.982.247-2')
  })

  it('formats complete CNPJ (14 digits)', () => {
    expect(maskCpfCnpj('12345678000190')).toBe('12.345.678/0001-90')
  })

  it('formats partial CNPJ - 12 digits', () => {
    expect(maskCpfCnpj('123456780001')).toBe('12.345.678/0001')
  })
})
