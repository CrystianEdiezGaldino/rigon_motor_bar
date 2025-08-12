
import React, { useState, useEffect } from 'react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import SEO from '../components/SEO';
import { getSEOConfig, getStructuredData } from '../lib/seo-config';
import { motion } from 'framer-motion';
import { Separator } from '@/components/ui/separator';

type MenuItem = {
  id: number;
  nome: string;
  descricao: string;
  preco: number;
  categoria: string;
  imagem: string;
  ativo: number;
};

type MenuCategory = {
  title: string;
  items: MenuItem[];
};

const MenuPage: React.FC = () => {
  const [menuCategories, setMenuCategories] = useState<MenuCategory[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  // Carregar produtos do banco de dados
  useEffect(() => {
    loadMenuData();
  }, []);

  const loadMenuData = async () => {
    try {
      const response = await fetch('/api/endpoints/cardapio-publico.php');
      if (response.ok) {
        const produtos: MenuItem[] = await response.json();
        
        // Agrupar produtos por categoria
        const categoriasMap = new Map<string, MenuItem[]>();
        
        produtos.forEach(produto => {
          if (produto.ativo) {
            if (!categoriasMap.has(produto.categoria)) {
              categoriasMap.set(produto.categoria, []);
            }
            categoriasMap.get(produto.categoria)!.push(produto);
          }
        });

        // Converter para o formato esperado pelo componente
        const categorias: MenuCategory[] = Array.from(categoriasMap.entries()).map(([categoria, items]) => ({
          title: categoria,
          items: items.sort((a, b) => a.nome.localeCompare(b.nome))
        }));

        setMenuCategories(categorias);
      } else {
        setError('Erro ao carregar o cardápio');
      }
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
      setError('Erro ao conectar com o servidor');
    } finally {
      setLoading(false);
    }
  };

  // Animation variants
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.15,
      },
    },
  };

  const itemVariants = {
    hidden: { y: 20, opacity: 0 },
    visible: {
      y: 0,
      opacity: 1,
      transition: {
        type: "spring",
        stiffness: 100,
      },
    },
  };

  const seoConfig = getSEOConfig('menu');
  const structuredData = getStructuredData('menu');

  if (loading) {
    return (
      <div className="min-h-screen bg-black text-white">
        <Navbar />
        <div className="pt-24 pb-16 px-4">
          <div className="container mx-auto max-w-4xl text-center">
            <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-[#F45F0A] mx-auto"></div>
            <p className="mt-4 text-lg">Carregando cardápio...</p>
          </div>
        </div>
        <Footer />
      </div>
    );
  }

  if (error) {
    return (
      <div className="min-h-screen bg-black text-white">
        <Navbar />
        <div className="pt-24 pb-16 px-4">
          <div className="container mx-auto max-w-4xl text-center">
            <div className="bg-red-900/50 p-8 rounded-lg">
              <h2 className="text-2xl font-bold text-red-400 mb-4">Erro ao carregar cardápio</h2>
              <p className="text-red-300">{error}</p>
              <button 
                onClick={loadMenuData}
                className="mt-4 px-6 py-2 bg-[#F45F0A] text-white rounded-lg hover:bg-[#F45F0A]/80 transition-colors"
              >
                Tentar novamente
              </button>
            </div>
          </div>
        </div>
        <Footer />
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-black text-white">
      <SEO 
        title={seoConfig.title}
        description={seoConfig.description}
        canonical={seoConfig.canonical}
        keywords={seoConfig.keywords}
        ogImage={seoConfig.ogImage}
        structuredData={structuredData}
      />
      
      <Navbar />
      
      <div className="pt-24 pb-16 px-4">
        <div className="container mx-auto max-w-4xl">
          <motion.h1 
            className="text-5xl font-bold text-center mb-2"
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
          >
            Cardápio
          </motion.h1>
          
          <motion.div 
            className="h-1 w-20 bg-[#F45F0A] mx-auto mb-12"
            initial={{ width: 0 }}
            animate={{ width: 80 }}
            transition={{ duration: 0.8, delay: 0.3 }}
          />
          
          {menuCategories.length === 0 ? (
            <motion.div 
              className="text-center py-16"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
            >
              <h2 className="text-2xl font-bold text-zinc-400 mb-4">Cardápio vazio</h2>
              <p className="text-zinc-500">Nenhum produto disponível no momento.</p>
            </motion.div>
          ) : (
            <motion.div 
              className="space-y-20"
              variants={containerVariants}
              initial="hidden"
              animate="visible"
            >
              {menuCategories.map((category, index) => (
                <motion.section 
                  key={index}
                  variants={itemVariants}
                  className="bg-zinc-900/50 backdrop-blur-sm p-8 rounded-lg"
                >
                  <h2 className="text-3xl font-bold mb-8 text-[#F45F0A] flex items-center">
                    {category.title}
                  </h2>
                  
                  <div className="space-y-6">
                    {category.items.map((item, itemIndex) => (
                      <motion.div 
                        key={item.id}
                        className="group"
                        whileHover={{ scale: 1.01 }}
                      >
                        <div className="flex justify-between items-baseline">
                          <h3 className="text-xl font-medium group-hover:text-[#F45F0A] transition-colors duration-300">
                            {item.nome}
                          </h3>
                          <div className="ml-4 text-lg font-medium text-[#F45F0A] flex-shrink-0">
                            R$ {item.preco.toFixed(2)}
                          </div>
                        </div>
                        
                        <p className="text-zinc-400 text-sm mt-1">
                          {item.descricao}
                        </p>
                        
                        {itemIndex < category.items.length - 1 && (
                          <Separator className="mt-5 bg-zinc-800" />
                        )}
                      </motion.div>
                    ))}
                  </div>
                </motion.section>
              ))}
            </motion.div>
          )}
        </div>
      </div>
      
      <Footer />
    </div>
  );
};

export default MenuPage;
