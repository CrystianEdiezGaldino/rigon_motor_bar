
import { useState, useEffect } from 'react';
import { Menu, X } from 'lucide-react';
import { Link, useLocation } from 'react-router-dom';

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [activeSection, setActiveSection] = useState('home');
  const location = useLocation();
  const isHomePage = location.pathname === '/';

  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 50) {
        setIsScrolled(true);
      } else {
        setIsScrolled(false);
      }

      // Only track sections on homepage
      if (isHomePage) {
        const sections = document.querySelectorAll('section[id]');
        let currentSection = '';
        
        sections.forEach(section => {
          const rect = section.getBoundingClientRect();
          const sectionTop = rect.top + window.pageYOffset;
          const sectionHeight = rect.height;
          const scrollPosition = window.scrollY + window.innerHeight / 3;

          if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
            currentSection = section.id;
          }
        });

        if (currentSection !== activeSection && currentSection !== '') {
          setActiveSection(currentSection);
        }
      }
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, [isHomePage]);

  const handleScrollTo = (elementId: string) => {
    setIsOpen(false);
    if (isHomePage) {
      const element = document.getElementById(elementId);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
      }
    } else {
      // If not on homepage, navigate to homepage first then scroll
      window.location.href = `/#${elementId}`;
    }
  };

  const navItems = [
    { label: 'Home', id: 'home' },
    { label: 'Sobre', id: 'sobre' },
    { label: 'Cardápio', id: 'cardapio', isRoute: true },
    { label: 'Eventos', id: 'eventos' },
    { label: 'Contato', id: 'contato' }
  ];

  const isActive = (item: typeof navItems[0]) => {
    if (item.isRoute) {
      return location.pathname === `/${item.id}`;
    }
    return isHomePage && activeSection === item.id;
  };

  return (
    <nav 
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${
        isScrolled 
          ? 'bg-black/95 backdrop-blur-md shadow-lg py-3 after:absolute after:inset-0 after:bg-gradient-to-b after:from-white/10 after:via-white/20 after:to-transparent after:blur-3xl after:-z-10' 
          : 'bg-gradient-to-b from-black/80 via-black/50 to-transparent py-6'
      }`}
    >
      <div className="container mx-auto px-6 flex justify-between items-center relative z-10">
        <Link 
          to="/"
          className="flex items-center text-white font-display text-2xl font-bold hover:text-[#F45F0A] transition-colors duration-300"
        >
          RIGON<span className="text-[#F45F0A]">MOTOR</span>BAR
        </Link>

        {/* Desktop Menu */}
        <div className="hidden md:flex items-center space-x-10">
          {navItems.map((item) => (
            item.isRoute ? (
              <Link
                key={item.label}
                to={`/${item.id}`}
                className={`text-white uppercase text-sm font-medium relative group transition-all duration-300 hover:text-[#F45F0A] ${
                  isActive(item) ? 'text-[#F45F0A]' : ''
                }`}
              >
                {item.label}
                <span className={`absolute left-0 right-0 bottom-0 h-0.5 bg-[#F45F0A] transform transition-transform duration-300 origin-center ${
                  isActive(item) ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100'
                }`}></span>
              </Link>
            ) : (
              <button
                key={item.label}
                onClick={() => handleScrollTo(item.id)}
                className={`text-white uppercase text-sm font-medium relative group transition-all duration-300 hover:text-[#F45F0A] ${
                  isActive(item) ? 'text-[#F45F0A]' : ''
                }`}
              >
                {item.label}
                <span className={`absolute left-0 right-0 bottom-0 h-0.5 bg-[#F45F0A] transform transition-transform duration-300 origin-center ${
                  isActive(item) ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100'
                }`}></span>
              </button>
            )
          ))}
        </div>

        {/* Mobile Menu Button */}
        <button
          className="md:hidden text-white"
          onClick={() => setIsOpen(!isOpen)}
        >
          {isOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </div>

      {/* Mobile Menu */}
      <div className={`md:hidden ${isOpen ? 'block' : 'hidden'}`}>
        <div className="px-4 py-2 bg-black/95 backdrop-blur-md">
          {navItems.map((item) => (
            item.isRoute ? (
              <Link
                key={item.label}
                to={`/${item.id}`}
                className={`block w-full text-left py-3 text-white hover:text-[#F45F0A] transition-colors ${
                  isActive(item) ? 'text-[#F45F0A]' : ''
                }`}
              >
                {item.label}
              </Link>
            ) : (
              <button
                key={item.label}
                onClick={() => handleScrollTo(item.id)}
                className={`block w-full text-left py-3 text-white hover:text-[#F45F0A] transition-colors ${
                  isActive(item) ? 'text-[#F45F0A]' : ''
                }`}
              >
                {item.label}
              </button>
            )
          ))}
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
