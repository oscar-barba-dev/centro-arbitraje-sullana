import React from 'react';
import { 
  ShieldCheck, Calculator, ArrowRight, Scales, UsersThree, 
  AddressBook, FilePdf, Lighthouse, Envelope, MapPin, Clock, Phone
} from 'lucide-react';
import Link from 'next/link';

export default function HomeReact() {
  return (
    <div className="font-sans text-stone-800 bg-stone-50 antialiased flex flex-col min-h-screen">
      
      {/* 1. Hero Section */}
      <section className="relative flex items-center min-h-[90vh] py-32 md:py-40">
        {/* Background Image with dark overlay matching the HTML version */}
        <div 
          className="absolute inset-0 z-0 bg-cover bg-center" 
          style={{ 
            backgroundImage: "url('https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80')",
          }}
        >
          <div className="absolute inset-0 bg-gradient-to-r from-emerald-900/85 to-stone-900/70"></div>
        </div>

        <div className="container mx-auto px-6 relative z-10 text-white">
          <div className="max-w-4xl animate-in fade-in slide-in-from-bottom-8 duration-1000">
            <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-yellow-500/20 text-yellow-500 mb-6 border border-yellow-500/30 backdrop-blur-sm">
              <ShieldCheck className="w-5 h-5" />
              <span className="text-sm font-semibold tracking-wide uppercase">Institución Líder en el Norte del País</span>
            </div>
            <h1 className="font-serif text-5xl md:text-7xl font-bold leading-tight mb-8">
              Resolución de Conflictos con <span className="text-yellow-500">Seguridad y Eficiencia</span>
            </h1>
            <p className="text-lg md:text-2xl text-stone-200 mb-12 max-w-3xl leading-relaxed">
              Garantizamos un proceso arbitral transparente y especializado, respaldado por la confianza institucional del Centro de Arbitraje de Sullana.
            </p>
            <div className="flex flex-wrap gap-5">
              <Link href="/tarifas" className="bg-yellow-500 text-emerald-900 px-8 py-4 rounded-xl hover:bg-yellow-600 transition-all font-bold text-lg shadow-xl hover:-translate-y-1 flex items-center gap-2">
                <Calculator className="w-5 h-5" /> Calcular Arancel
              </Link>
              <Link href="/servicios" className="bg-white/10 backdrop-blur-md border border-white/30 text-white px-8 py-4 rounded-xl hover:bg-white/20 transition-all font-bold text-lg flex items-center gap-2">
                <ArrowRight className="w-5 h-5" /> Conoce nuestros servicios
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* 2. Tarjetas de Servicios */}
      <section className="py-24 bg-white relative -mt-16 z-20">
        <div className="container mx-auto px-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {/* Tarjeta Arbitraje */}
            <div className="bg-white rounded-3xl shadow-2xl border border-stone-100 overflow-hidden flex flex-col group transition-transform duration-300 hover:-translate-y-2">
              <div className="h-64 overflow-hidden relative">
                <div className="absolute inset-0 bg-emerald-900/20 z-10 group-hover:bg-transparent transition-colors duration-500"></div>
                <img src="https://images.unsplash.com/photo-1589391886645-d51941baf7fb?auto=format&fit=crop&q=80" alt="Arbitraje" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                <div className="absolute top-4 left-4 z-20 bg-white/90 backdrop-blur-sm p-3 rounded-2xl shadow-lg text-emerald-900">
                  <Scales className="w-8 h-8" />
                </div>
              </div>
              <div className="p-10 flex-grow flex flex-col justify-between">
                <div>
                  <h3 className="font-serif text-3xl font-bold text-emerald-900 mb-4">Arbitraje Institucional</h3>
                  <p className="text-stone-600 text-lg leading-relaxed mb-8">
                    Administración integral y especializada de controversias bajo nuestro reglamento, garantizando celeridad, confidencialidad y laudos con plena validez legal.
                  </p>
                </div>
                <Link href="/servicios" className="inline-flex items-center gap-2 text-yellow-600 font-bold text-lg hover:text-emerald-900 transition-colors group/link">
                  Ver más detalles <ArrowRight className="w-5 h-5 group-hover/link:translate-x-1 transition-transform" />
                </Link>
              </div>
            </div>

            {/* Tarjeta JRD */}
            <div className="bg-white rounded-3xl shadow-2xl border border-stone-100 overflow-hidden flex flex-col group transition-transform duration-300 hover:-translate-y-2">
              <div className="h-64 overflow-hidden relative">
                <div className="absolute inset-0 bg-emerald-900/20 z-10 group-hover:bg-transparent transition-colors duration-500"></div>
                <img src="https://images.unsplash.com/photo-1505664159854-2328109d7224?auto=format&fit=crop&q=80" alt="Junta de Resolución de Disputas" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                <div className="absolute top-4 left-4 z-20 bg-white/90 backdrop-blur-sm p-3 rounded-2xl shadow-lg text-emerald-900">
                  <UsersThree className="w-8 h-8" />
                </div>
              </div>
              <div className="p-10 flex-grow flex flex-col justify-between">
                <div>
                  <h3 className="font-serif text-3xl font-bold text-emerald-900 mb-4">Junta de Resolución de Disputas (JRD)</h3>
                  <p className="text-stone-600 text-lg leading-relaxed mb-8">
                    Acompañamiento especializado durante la ejecución de obras públicas o privadas para prevenir y resolver conflictos en tiempo real, evitando paralizaciones.
                  </p>
                </div>
                <Link href="/servicios" className="inline-flex items-center gap-2 text-yellow-600 font-bold text-lg hover:text-emerald-900 transition-colors group/link">
                  Ver más detalles <ArrowRight className="w-5 h-5 group-hover/link:translate-x-1 transition-transform" />
                </Link>
              </div>
            </div>

          </div>
        </div>
      </section>

      {/* 3. Cinta de Métricas de Confianza */}
      <section className="py-20 bg-emerald-900 relative overflow-hidden">
        <div className="absolute inset-0 opacity-5 bg-[url('https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80')] bg-cover bg-center"></div>
        <div className="container mx-auto px-6 relative z-10">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-12 text-center divide-y md:divide-y-0 md:divide-x divide-emerald-800">
            <div className="py-4">
              <span className="block font-serif text-6xl font-bold text-yellow-500 mb-2">+15</span>
              <span className="block text-emerald-100 text-lg font-medium uppercase tracking-wider">Años de Experiencia</span>
            </div>
            <div className="py-4">
              <span className="block font-serif text-6xl font-bold text-yellow-500 mb-2">+50</span>
              <span className="block text-emerald-100 text-lg font-medium uppercase tracking-wider">Árbitros Especializados</span>
            </div>
            <div className="py-4">
              <span className="block font-serif text-6xl font-bold text-yellow-500 mb-2">+1,200</span>
              <span className="block text-emerald-100 text-lg font-medium uppercase tracking-wider">Casos Atendidos</span>
            </div>
          </div>
        </div>
      </section>

      {/* 4. Grid de Herramientas (Accesos Rápidos) */}
      <section className="py-24 bg-stone-50">
        <div className="container mx-auto px-6">
          <div className="text-center max-w-2xl mx-auto mb-16">
            <h2 className="font-serif text-4xl font-bold text-emerald-900 mb-4">Herramientas y Accesos Rápidos</h2>
            <div className="w-20 h-1.5 bg-yellow-500 mx-auto rounded-full"></div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <Link href="/servicios" className="bg-white p-8 rounded-3xl shadow-sm hover:shadow-2xl border border-stone-200 transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2">
              <div className="w-20 h-20 bg-stone-50 rounded-full flex items-center justify-center mb-6 text-emerald-900 group-hover:bg-emerald-900 group-hover:text-yellow-500 transition-colors">
                <AddressBook className="w-10 h-10" />
              </div>
              <h3 className="font-serif text-xl font-bold text-stone-800 mb-3">Directorio de Árbitros y Tarifas</h3>
              <p className="text-stone-600">Consulte nuestra nómina oficial y la tabla de aranceles vigentes.</p>
            </Link>
            
            <Link href="#" className="bg-white p-8 rounded-3xl shadow-sm hover:shadow-2xl border border-stone-200 transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2">
              <div className="w-20 h-20 bg-stone-50 rounded-full flex items-center justify-center mb-6 text-emerald-900 group-hover:bg-emerald-900 group-hover:text-yellow-500 transition-colors">
                <FilePdf className="w-10 h-10" />
              </div>
              <h3 className="font-serif text-xl font-bold text-stone-800 mb-3">Descarga de Cláusula Arbitral</h3>
              <p className="text-stone-600">Modelos de cláusulas estándar para incorporar en sus contratos.</p>
            </Link>

            <Link href="#" className="bg-white p-8 rounded-3xl shadow-sm hover:shadow-2xl border border-stone-200 transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2">
              <div className="w-20 h-20 bg-stone-50 rounded-full flex items-center justify-center mb-6 text-emerald-900 group-hover:bg-emerald-900 group-hover:text-yellow-500 transition-colors">
                <Lighthouse className="w-10 h-10" />
              </div>
              <h3 className="font-serif text-xl font-bold text-stone-800 mb-3">Faro de Transparencia</h3>
              <p className="text-stone-600">Acceda a laudos anonimizados y estadísticas de nuestros procesos.</p>
            </Link>
          </div>
        </div>
      </section>

      {/* 5. Sección de Actualidad/Blog */}
      <section className="py-24 bg-white">
        <div className="container mx-auto px-6">
          <div className="flex justify-between items-end mb-16">
            <div>
              <h2 className="font-serif text-4xl font-bold text-emerald-900 mb-4">Actualidad Institucional</h2>
              <div className="w-20 h-1.5 bg-yellow-500 rounded-full"></div>
            </div>
            <Link href="#" className="hidden md:flex items-center gap-2 text-emerald-900 font-bold hover:text-yellow-600 transition-colors">
              Ver todas las noticias <ArrowRight className="w-5 h-5" />
            </Link>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <article className="bg-stone-50 rounded-3xl overflow-hidden border border-stone-100 shadow-sm hover:shadow-xl transition-shadow group">
              <div className="h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&q=80" alt="Noticia 1" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              </div>
              <div className="p-8">
                <span className="text-xs font-bold text-yellow-600 uppercase tracking-widest mb-3 block">12 Marzo, 2024</span>
                <h3 className="font-serif text-xl font-bold text-emerald-900 mb-4 line-clamp-2 hover:text-yellow-600 transition-colors cursor-pointer">
                  Nuevo Reglamento de Arbitraje entra en vigencia este mes
                </h3>
                <Link href="#" className="text-stone-500 font-medium text-sm flex items-center gap-2 hover:text-emerald-900 transition-colors">
                  Leer artículo <ArrowRight className="w-4 h-4" />
                </Link>
              </div>
            </article>

            <article className="bg-stone-50 rounded-3xl overflow-hidden border border-stone-100 shadow-sm hover:shadow-xl transition-shadow group">
              <div className="h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80" alt="Noticia 2" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              </div>
              <div className="p-8">
                <span className="text-xs font-bold text-yellow-600 uppercase tracking-widest mb-3 block">05 Marzo, 2024</span>
                <h3 className="font-serif text-xl font-bold text-emerald-900 mb-4 line-clamp-2 hover:text-yellow-600 transition-colors cursor-pointer">
                  Conferencia: El impacto de la JRD en las obras públicas de la región
                </h3>
                <Link href="#" className="text-stone-500 font-medium text-sm flex items-center gap-2 hover:text-emerald-900 transition-colors">
                  Leer artículo <ArrowRight className="w-4 h-4" />
                </Link>
              </div>
            </article>

            <article className="bg-stone-50 rounded-3xl overflow-hidden border border-stone-100 shadow-sm hover:shadow-xl transition-shadow group">
              <div className="h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1450101499163-c8848c66cb85?auto=format&fit=crop&q=80" alt="Noticia 3" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              </div>
              <div className="p-8">
                <span className="text-xs font-bold text-yellow-600 uppercase tracking-widest mb-3 block">28 Febrero, 2024</span>
                <h3 className="font-serif text-xl font-bold text-emerald-900 mb-4 line-clamp-2 hover:text-yellow-600 transition-colors cursor-pointer">
                  Incorporación de 10 nuevos especialistas a nuestra nómina de árbitros
                </h3>
                <Link href="#" className="text-stone-500 font-medium text-sm flex items-center gap-2 hover:text-emerald-900 transition-colors">
                  Leer artículo <ArrowRight className="w-4 h-4" />
                </Link>
              </div>
            </article>
          </div>
        </div>
      </section>

      {/* 6. Suscripción al Boletín */}
      <section className="py-20 bg-stone-100 border-y border-stone-200">
        <div className="container mx-auto px-6">
          <div className="bg-emerald-900 rounded-3xl p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-12 relative overflow-hidden shadow-2xl">
            <div className="absolute -right-20 -top-20 opacity-10">
              <Envelope className="w-64 h-64" />
            </div>
            <div className="md:w-1/2 relative z-10">
              <h2 className="font-serif text-3xl md:text-4xl font-bold text-white mb-4">Suscríbase a nuestro boletín</h2>
              <p className="text-emerald-100 text-lg">Reciba las últimas normativas, artículos de interés y convocatorias a eventos académicos en su correo electrónico.</p>
            </div>
            <div className="md:w-1/2 w-full relative z-10">
              <form className="flex flex-col gap-4" onSubmit={(e) => e.preventDefault()}>
                <div className="flex flex-col sm:flex-row gap-4">
                  <input type="text" placeholder="Su Nombre" required className="w-full px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 text-stone-800 font-medium" />
                  <input type="email" placeholder="Su Correo Electrónico" required className="w-full px-5 py-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 text-stone-800 font-medium" />
                </div>
                <button type="submit" className="bg-yellow-500 text-emerald-900 font-bold py-4 rounded-xl hover:bg-yellow-600 transition-colors shadow-lg text-lg flex justify-center items-center gap-2">
                  Suscribirse <ArrowRight className="w-5 h-5" />
                </button>
              </form>
            </div>
          </div>
        </div>
      </section>

      {/* 7. Footer Completo */}
      <footer className="bg-stone-900 text-stone-300 pt-20 pb-10 border-t-8 border-yellow-500 mt-auto">
        <div className="container mx-auto px-6">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            
            <div>
              <div className="flex items-center gap-3 mb-6">
                <div className="bg-emerald-900 p-2 rounded-lg">
                  <Scales className="text-yellow-500 w-6 h-6" />
                </div>
                <div>
                  <span className="font-serif font-bold text-xl text-white block">Centro de Arbitraje</span>
                  <span className="text-xs tracking-widest uppercase text-stone-400">de Sullana</span>
                </div>
              </div>
              <p className="text-stone-400 text-sm leading-relaxed mb-6">
                Institución líder en la resolución de controversias mediante el arbitraje, garantizando imparcialidad, celeridad y seguridad jurídica.
              </p>
            </div>

            <div>
              <h4 className="font-serif text-lg font-bold text-white mb-6">Sede Central</h4>
              <ul className="space-y-4 text-stone-400 text-sm">
                <li className="flex items-start gap-3">
                  <MapPin className="text-yellow-500 w-5 h-5 shrink-0" />
                  <span>Calle San Martín N.º 1021<br/>Sullana, Piura</span>
                </li>
                <li className="flex items-start gap-3">
                  <Clock className="text-yellow-500 w-5 h-5 shrink-0" />
                  <span>Lunes a Viernes<br/>08:30 a.m. - 05:30 p.m.</span>
                </li>
              </ul>
            </div>

            <div>
              <h4 className="font-serif text-lg font-bold text-white mb-6">Contáctenos</h4>
              <ul className="space-y-4 text-stone-400 text-sm">
                <li className="flex flex-col gap-1">
                  <span className="text-white font-medium">Mesa de Partes Virtual:</span>
                  <a href="mailto:mesadepartes@arbitrajesullana.pe" className="hover:text-yellow-500 transition-colors flex items-center gap-2">
                    <Envelope className="w-4 h-4 text-yellow-500" /> mesadepartes@arbitrajesullana.pe
                  </a>
                </li>
                <li className="flex flex-col gap-1">
                  <span className="text-white font-medium">Informes y Consultas:</span>
                  <a href="mailto:informes@arbitrajesullana.pe" className="hover:text-yellow-500 transition-colors flex items-center gap-2">
                    <Envelope className="w-4 h-4 text-yellow-500" /> informes@arbitrajesullana.pe
                  </a>
                </li>
                <li className="flex items-center gap-2 mt-2">
                  <Phone className="text-yellow-500 w-5 h-5" />
                  <span className="font-medium text-white">+51 (073) 123 456</span>
                </li>
              </ul>
            </div>

            <div>
              <h4 className="font-serif text-lg font-bold text-white mb-6">Marco Legal</h4>
              <ul className="space-y-3 text-stone-400 text-sm">
                <li><Link href="#" className="hover:text-yellow-500 transition-colors flex items-center gap-2"><ArrowRight className="w-4 h-4 text-yellow-500" /> Estatuto del Centro</Link></li>
                <li><Link href="#" className="hover:text-yellow-500 transition-colors flex items-center gap-2"><ArrowRight className="w-4 h-4 text-yellow-500" /> Reglamento de Arbitraje</Link></li>
                <li><Link href="#" className="hover:text-yellow-500 transition-colors flex items-center gap-2"><ArrowRight className="w-4 h-4 text-yellow-500" /> Código de �0tica</Link></li>
                <li><Link href="#" className="hover:text-yellow-500 transition-colors flex items-center gap-2"><ArrowRight className="w-4 h-4 text-yellow-500" /> Políticas de Privacidad</Link></li>
              </ul>
            </div>

          </div>

          <div className="border-t border-stone-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-stone-500">
            <p>&copy; {new Date().getFullYear()} Centro de Arbitraje de Sullana. Todos los derechos reservados.</p>
            <p>Diseñado con estándares de <span className="text-stone-400 font-medium">Seguridad Jurídica</span></p>
          </div>
        </div>
      </footer>

    </div>
  );
}