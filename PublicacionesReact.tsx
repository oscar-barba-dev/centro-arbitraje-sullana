"use client";

import React, { useState, useEffect } from 'react';
import { BookOpen, Download, ArrowRight, FileText, Scales } from 'lucide-react';
import Link from 'next/link';

// Interfaz TypeScript para tipar los datos de la base de datos
interface Publicacion {
  id: number;
  titulo: string;
  portada: string;
  categoria: string;
  fecha_publicacion: string;
  resumen: string;
  enlace: string;
  status: string;
}

// Datos simulados (En producción, estos vendrían de un fetch a tu API de Next.js conectada a MySQL)
const mockPublicaciones: Publicacion[] = [
  {
    id: 1,
    titulo: 'Guía Práctica de Arbitraje Institucional',
    portada: 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=600',
    categoria: 'Instructivo',
    fecha_publicacion: '2023-10-15',
    resumen: 'Conoce el paso a paso para iniciar un proceso arbitral en nuestro centro.',
    enlace: '#',
    status: 'publicado'
  },
  {
    id: 2,
    titulo: 'Reglamento de Arbitraje 2024',
    portada: 'https://images.unsplash.com/photo-1505664159854-2328109d7224?auto=format&fit=crop&q=80&w=600',
    categoria: 'Instructivo',
    fecha_publicacion: '2024-01-10',
    resumen: 'Documento oficial con las normativas actualizadas para el presente año.',
    enlace: '#',
    status: 'publicado'
  },
  {
    id: 3,
    titulo: 'El Arbitraje Comercial en el Perú',
    portada: 'https://images.unsplash.com/photo-1589391886645-d51941baf7fb?auto=format&fit=crop&q=80&w=600',
    categoria: 'Libros',
    fecha_publicacion: '2022-05-20',
    resumen: 'Análisis profundo de la jurisprudencia arbitral comercial.',
    enlace: '#',
    status: 'publicado'
  },
  {
    id: 4,
    titulo: 'Manual de Junta de Resolución de Disputas',
    portada: 'https://images.unsplash.com/photo-1450101499163-c8848c66cb85?auto=format&fit=crop&q=80&w=600',
    categoria: 'Libros',
    fecha_publicacion: '2023-11-05',
    resumen: 'Guía completa sobre la implementación y funcionamiento de las JRD.',
    enlace: '#',
    status: 'publicado'
  }
];

export default function PublicacionesPage() {
  const [publicaciones, setPublicaciones] = useState<Publicacion[]>([]);
  const [categoriaActiva, setCategoriaActiva] = useState<string>('Todas');

  // Simular la carga de datos desde la API
  useEffect(() => {
    // Aquí iría: fetch('/api/publicaciones').then(res => res.json()).then(data => setPublicaciones(data));
    setPublicaciones(mockPublicaciones);
  }, []);

  const categorias = ['Todas', 'Instructivo', 'Libros'];

  const publicacionesFiltradas = publicaciones.filter(pub => 
    categoriaActiva === 'Todas' ? true : pub.categoria === categoriaActiva
  );

  return (
    <div className="font-sans text-stone-800 bg-stone-50 min-h-screen flex flex-col">
      
      {/* Navegación (Ejemplo de cómo debe quedar con el nuevo enlace) */}
      <header className="sticky top-0 z-50 w-full transition-all duration-300 bg-white/95 backdrop-blur-md shadow-sm border-b border-stone-200">
        <div className="container mx-auto px-6 h-20 flex items-center justify-between">
          <Link href="/" className="flex items-center gap-3 group">
            <div className="p-2 bg-emerald-900 rounded-lg group-hover:bg-emerald-800 transition-colors shadow-md">
              <Scales className="text-yellow-500 w-6 h-6" />
            </div>
            <div className="flex flex-col">
              <span className="font-serif font-bold text-xl leading-tight text-emerald-900">Centro de Arbitraje</span>
              <span className="text-xs font-medium text-stone-500 uppercase tracking-widest">de Sullana</span>
            </div>
          </Link>
          <nav className="hidden lg:flex items-center gap-8 font-medium">
            <Link href="/" className="text-stone-600 hover:text-emerald-900 transition-colors">Inicio</Link>
            <Link href="/nosotros" className="text-stone-600 hover:text-emerald-900 transition-colors">Nosotros</Link>
            <Link href="/servicios" className="text-stone-600 hover:text-emerald-900 transition-colors">Servicios</Link>
            <Link href="/publicaciones" className="text-emerald-900 font-bold transition-colors border-b-2 border-emerald-900">Publicaciones</Link>
            <Link href="/tarifas" className="text-stone-600 hover:text-emerald-900 transition-colors">Tarifas</Link>
            <Link href="/contacto" className="bg-emerald-900 text-white px-5 py-2.5 rounded-md hover:bg-emerald-800 transition-colors font-semibold shadow-md">Contactar</Link>
          </nav>
        </div>
      </header>

      <main className="flex-grow">
        {/* Banner Hero */}
        <section className="relative py-24 md:py-32 flex items-center overflow-hidden">
          <div 
            className="absolute inset-0 z-0 bg-cover bg-center" 
            style={{ backgroundImage: "url('https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&q=80')" }}
          >
            <div className="absolute inset-0 bg-gradient-to-r from-emerald-900/90 to-stone-900/80 mix-blend-multiply"></div>
          </div>
          
          <div className="container mx-auto px-6 relative z-10 text-center animate-in fade-in slide-in-from-bottom-8 duration-1000">
            <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-yellow-500/20 text-yellow-500 mb-6 border border-yellow-500/30 backdrop-blur-sm">
              <BookOpen className="w-5 h-5" />
              <span className="text-sm font-semibold tracking-wide uppercase">Biblioteca Institucional</span>
            </div>
            <h1 className="font-serif text-5xl md:text-6xl font-bold text-white mb-6">
              Publicaciones
            </h1>
            <p className="text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
              Acceda a nuestra colección de reglamentos, manuales instructivos y material académico especializado en resolución de controversias.
            </p>
          </div>
        </section>

        {/* Diseño de Contenido Principal y Sidebar */}
        <section className="py-20 bg-stone-50">
          <div className="container mx-auto px-6">
            <div className="flex flex-col lg:flex-row gap-12">
              
              {/* Contenido Principal (Cuadrícula de Publicaciones - 3/4 ancho) */}
              <div className="lg:w-3/4 order-2 lg:order-1">
                
                {/* Resultados Header */}
                <div className="mb-8 flex justify-between items-center border-b border-stone-200 pb-4">
                  <h2 className="font-serif text-2xl font-bold text-emerald-900">
                    {categoriaActiva === 'Todas' ? 'Todas las Publicaciones' : `Categoría: ${categoriaActiva}`}
                  </h2>
                  <span className="text-stone-500 text-sm font-medium bg-white px-3 py-1 rounded-full border border-stone-200">
                    {publicacionesFiltradas.length} resultados
                  </span>
                </div>

                {/* Cuadrícula Responsiva (2 columnas en escritorio, 1 o 2 en móvil) */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8">
                  {publicacionesFiltradas.map((pub) => (
                    <article key={pub.id} className="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col h-full animate-in fade-in zoom-in-95">
                      {/* Portada */}
                      <div className="h-56 overflow-hidden relative">
                        <div className="absolute inset-0 bg-emerald-900/10 z-10 group-hover:bg-transparent transition-colors duration-500"></div>
                        <img 
                          src={pub.portada} 
                          alt={pub.titulo} 
                          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                        />
                        <div className="absolute top-4 right-4 z-20">
                          <span className="bg-yellow-500 text-emerald-900 text-xs font-bold px-3 py-1 rounded-full shadow-md uppercase tracking-wider">
                            {pub.categoria}
                          </span>
                        </div>
                      </div>
                      
                      {/* Contenido Tarjeta */}
                      <div className="p-6 flex flex-col flex-grow">
                        <span className="text-stone-400 text-xs font-medium mb-3 flex items-center gap-1">
                          <FileText className="w-3 h-3" /> Publicado el {pub.fecha_publicacion}
                        </span>
                        <h3 className="font-serif text-xl font-bold text-emerald-900 mb-3 line-clamp-2">
                          {pub.titulo}
                        </h3>
                        <p className="text-stone-600 text-sm mb-6 flex-grow line-clamp-3">
                          {pub.resumen}
                        </p>
                        
                        {/* Enlace/Botón */}
                        <div className="mt-auto pt-4 border-t border-stone-100">
                          <Link 
                            href={pub.enlace} 
                            className="inline-flex items-center gap-2 text-emerald-900 font-bold text-sm hover:text-yellow-600 transition-colors group/btn"
                          >
                            <Download className="w-4 h-4" /> 
                            Descargar / Ver
                            <ArrowRight className="w-4 h-4 group-hover/btn:translate-x-1 transition-transform ml-auto" />
                          </Link>
                        </div>
                      </div>
                    </article>
                  ))}

                  {publicacionesFiltradas.length === 0 && (
                    <div className="col-span-full py-12 text-center bg-white rounded-2xl border border-stone-200 border-dashed">
                      <BookOpen className="w-12 h-12 text-stone-300 mx-auto mb-4" />
                      <h3 className="text-lg font-bold text-stone-500">No hay publicaciones en esta categoría.</h3>
                    </div>
                  )}
                </div>
              </div>

              {/* Sidebar (Derecha - 1/4 ancho) */}
              <aside className="lg:w-1/4 order-1 lg:order-2">
                <div className="bg-white rounded-3xl p-6 shadow-lg border border-stone-100 sticky top-28">
                  <h3 className="font-serif text-xl font-bold text-emerald-900 mb-6 flex items-center gap-2">
                    <FileText className="w-5 h-5 text-yellow-500" /> Categorías
                  </h3>
                  
                  <ul className="space-y-3">
                    {categorias.map((cat) => (
                      <li key={cat}>
                        <button
                          onClick={() => setCategoriaActiva(cat)}
                          className={`w-full text-left px-4 py-3 rounded-xl transition-all duration-200 font-medium flex justify-between items-center ${
                            categoriaActiva === cat 
                              ? 'bg-emerald-900 text-yellow-500 shadow-md' 
                              : 'bg-stone-50 text-stone-600 hover:bg-emerald-50 hover:text-emerald-900'
                          }`}
                        >
                          {cat}
                          {categoriaActiva === cat && <ArrowRight className="w-4 h-4" />}
                        </button>
                      </li>
                    ))}
                  </ul>

                  {/* Bloque promocional extra en sidebar */}
                  <div className="mt-8 p-5 bg-gradient-to-br from-emerald-900 to-emerald-800 rounded-2xl text-white text-center">
                    <Scales className="w-8 h-8 text-yellow-500 mx-auto mb-3" />
                    <h4 className="font-bold mb-2">¿Necesita asesoría legal?</h4>
                    <p className="text-sm text-emerald-100 mb-4 opacity-90">Consulte directamente con nuestra secretaría técnica.</p>
                    <Link href="/contacto" className="block w-full py-2 bg-yellow-500 text-emerald-900 font-bold rounded-lg hover:bg-yellow-400 transition-colors text-sm">
                      Contáctenos
                    </Link>
                  </div>
                </div>
              </aside>

            </div>
          </div>
        </section>
      </main>
    </div>
  );
}