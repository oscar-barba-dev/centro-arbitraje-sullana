"use client";

import React, { useState } from 'react';
import { Calculator, Scale, Users, User, Receipt } from 'lucide-react';

type TipoCaso = 'cuantificable' | 'incuantificable' | 'desalojo';
type TipoTribunal = 'unico' | 'colegiado';

export default function TariffCalculator() {
  const [tipoCaso, setTipoCaso] = useState<TipoCaso>('cuantificable');
  const [tribunal, setTribunal] = useState<TipoTribunal>('unico');
  const [cuantia, setCuantia] = useState<number | ''>('');

  const UIT = 5150; // Valor referencial UIT

  const calcularCostos = () => {
    let gastosAdmin = 0;
    let honorarios = 0;
    const monto = Number(cuantia);

    if (tipoCaso === 'incuantificable') {
      gastosAdmin = UIT * 1;
      honorarios = UIT * 2.5;
    } else if (tipoCaso === 'desalojo') {
      gastosAdmin = UIT * 0.5;
      honorarios = UIT * 1.5;
    } else {
      // Caso Cuantificable (Tramos escalonados referenciales)
      if (monto <= 0) {
        gastosAdmin = 0;
        honorarios = 0;
      } else if (monto <= 50000) {
        gastosAdmin = 1500;
        honorarios = 2500;
      } else if (monto <= 100000) {
        gastosAdmin = 2000;
        honorarios = 4000;
      } else if (monto <= 500000) {
        gastosAdmin = 2000 + (monto - 100000) * 0.015;
        honorarios = 4000 + (monto - 100000) * 0.03;
      } else {
        gastosAdmin = 8000 + (monto - 500000) * 0.01;
        honorarios = 16000 + (monto - 500000) * 0.02;
      }
    }

    // Multiplicador por Tribunal Colegiado (3 árbitros)
    if (tribunal === 'colegiado' && honorarios > 0) {
      honorarios = honorarios * 3;
    }

    const subtotal = gastosAdmin + honorarios;
    const igv = subtotal * 0.18;
    const total = subtotal + igv;

    return { gastosAdmin, honorarios, subtotal, igv, total };
  };

  const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('es-PE', {
      style: 'currency',
      currency: 'PEN',
      minimumFractionDigits: 2
    }).format(value);
  };

  const costos = calcularCostos();

  return (
    <div className="bg-stone-50 rounded-3xl shadow-2xl border border-stone-200 overflow-hidden max-w-5xl mx-auto font-sans text-stone-800">
      
      {/* Header */}
      <div className="bg-emerald-900 p-8 text-white flex items-center gap-6 relative overflow-hidden">
        <div className="absolute -right-10 -top-10 opacity-10">
          <Scale size={200} />
        </div>
        <div className="relative z-10 flex items-center gap-5">
          <div className="p-4 bg-yellow-600 rounded-2xl shadow-lg">
            <Calculator className="text-emerald-900" size={32} />
          </div>
          <div>
            <h2 className="font-serif text-3xl font-bold text-white">Calculadora de Aranceles</h2>
            <p className="text-emerald-100/80 mt-1 font-medium tracking-wide uppercase text-sm">Centro de Arbitraje de Sullana</p>
          </div>
        </div>
      </div>

      <div className="p-8 md:p-12 grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        {/* Panel de Entradas */}
        <div className="lg:col-span-7 space-y-8">
          
          {/* 1. Tipo de Caso */}
          <div>
            <label className="block text-sm font-bold text-stone-700 mb-3 uppercase tracking-wide">
              1. Tipo de Caso
            </label>
            <div className="relative">
              <select 
                value={tipoCaso} 
                onChange={(e) => {
                  setTipoCaso(e.target.value as TipoCaso);
                  if (e.target.value !== 'cuantificable') setCuantia('');
                }}
                className="w-full appearance-none bg-white border-2 border-stone-200 text-stone-800 py-4 pl-5 pr-10 rounded-2xl focus:outline-none focus:border-yellow-600 focus:ring-0 font-medium text-lg transition-colors shadow-sm"
              >
                <option value="cuantificable">Controversia Cuantificable</option>
                <option value="incuantificable">Controversia Incuantificable</option>
                <option value="desalojo">Proceso de Desalojo</option>
              </select>
              <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-stone-500">
                <svg className="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
            </div>
          </div>

          {/* 2. Composición del Tribunal */}
          <div>
            <label className="block text-sm font-bold text-stone-700 mb-3 uppercase tracking-wide">
              2. Composición del Tribunal
            </label>
            <div className="grid grid-cols-2 gap-4">
              <button 
                onClick={() => setTribunal('unico')}
                className={`flex flex-col items-center justify-center py-5 px-4 rounded-2xl border-2 transition-all duration-200 ${tribunal === 'unico' ? 'border-yellow-600 bg-yellow-50 text-emerald-900 shadow-md' : 'border-stone-200 bg-white text-stone-500 hover:border-stone-300 hover:bg-stone-50'}`}
              >
                <User size={28} className={tribunal === 'unico' ? 'text-yellow-600 mb-2' : 'mb-2'} />
                <span className="font-bold">Árbitro Único</span>
              </button>
              <button 
                onClick={() => setTribunal('colegiado')}
                className={`flex flex-col items-center justify-center py-5 px-4 rounded-2xl border-2 transition-all duration-200 ${tribunal === 'colegiado' ? 'border-yellow-600 bg-yellow-50 text-emerald-900 shadow-md' : 'border-stone-200 bg-white text-stone-500 hover:border-stone-300 hover:bg-stone-50'}`}
              >
                <Users size={28} className={tribunal === 'colegiado' ? 'text-yellow-600 mb-2' : 'mb-2'} />
                <span className="font-bold">Tribunal Colegiado (3)</span>
              </button>
            </div>
          </div>

          {/* 3. Cuantía (Condicional) */}
          {tipoCaso === 'cuantificable' && (
            <div className="animate-in fade-in slide-in-from-top-4 duration-300">
              <label className="block text-sm font-bold text-stone-700 mb-3 uppercase tracking-wide">
                3. Monto en Disputa (S/)
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                  <span className="text-stone-400 font-bold text-xl">S/</span>
                </div>
                <input 
                  type="number" 
                  min="0"
                  value={cuantia}
                  onChange={(e) => setCuantia(e.target.value ? Number(e.target.value) : '')}
                  placeholder="Ej. 150000"
                  className="w-full pl-14 pr-6 py-5 bg-white border-2 border-stone-200 rounded-2xl focus:outline-none focus:border-yellow-600 text-2xl font-bold text-stone-800 shadow-sm transition-colors"
                />
              </div>
            </div>
          )}
        </div>

        {/* Panel de Resultados (Voucher Elegante) */}
        <div className="lg:col-span-5">
          <div className="bg-white rounded-3xl p-8 shadow-[0_10px_40px_-15px_rgba(0,0,0,0.1)] border border-stone-100 relative h-full flex flex-col">
            
            {/* Adorno superior voucher */}
            <div className="absolute top-0 left-0 w-full h-3 bg-gradient-to-r from-emerald-900 via-emerald-700 to-yellow-600 rounded-t-3xl"></div>
            
            <div className="flex items-center gap-3 mb-8 border-b border-stone-100 pb-6 mt-2">
              <Receipt className="text-yellow-600" size={28} />
              <h3 className="font-serif text-2xl font-bold text-emerald-900">Resumen Estimado</h3>
            </div>

            <div className="space-y-6 flex-grow">
              <div className="flex justify-between items-end">
                <span className="text-stone-500 font-medium leading-tight">Gastos<br/>Administrativos</span>
                <span className="font-bold text-stone-800 text-lg">{formatCurrency(costos.gastosAdmin)}</span>
              </div>
              <div className="flex justify-between items-end">
                <span className="text-stone-500 font-medium leading-tight">Honorarios<br/>Arbitrales</span>
                <span className="font-bold text-stone-800 text-lg">{formatCurrency(costos.honorarios)}</span>
              </div>
              
              <div className="pt-6 border-t border-dashed border-stone-300">
                <div className="flex justify-between items-center mb-3">
                  <span className="text-stone-500 font-medium">Subtotal</span>
                  <span className="font-bold text-stone-800">{formatCurrency(costos.subtotal)}</span>
                </div>
                <div className="flex justify-between items-center">
                  <span className="text-stone-500 font-medium">IGV (18%)</span>
                  <span className="font-bold text-stone-800">{formatCurrency(costos.igv)}</span>
                </div>
              </div>
            </div>

            <div className="mt-8 pt-6 border-t-2 border-stone-800 flex flex-col items-center bg-stone-50 -mx-8 -mb-8 p-8 rounded-b-3xl">
              <span className="font-bold text-stone-500 uppercase tracking-widest text-xs mb-2">Costo Total Estimado</span>
              <span className="font-bold text-4xl text-emerald-900 drop-shadow-sm">{formatCurrency(costos.total)}</span>
              
              <p className="text-center text-[10px] text-stone-400 mt-6 leading-relaxed max-w-xs">
                * El presente cálculo es una simulación referencial no vinculante. Los montos definitivos serán determinados por la Secretaría General de acuerdo a las particularidades del caso y el Arancel vigente.
              </p>
            </div>

          </div>
        </div>
      </div>
    </div>
  );
}