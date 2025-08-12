
import React from 'react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import SEO from '../components/SEO';
import { getSEOConfig, getStructuredData } from '../lib/seo-config';
import { motion } from 'framer-motion';
import { Separator } from '@/components/ui/separator';

type MenuCategory = {
  title: string;
  items: {
    name: string;
    description: string;
    price: string;
  }[];
};

const MenuPage: React.FC = () => {
  const menuCategories: MenuCategory[] = [
    {
      title: "Hambúrgueres",
      items: [
        {
          name: "Road Burger",
          description: "Hambúrguer artesanal, queijo cheddar, bacon crocante, cebola caramelizada e molho especial da casa",
          price: "R$ 39,90"
        },
        {
          name: "Highway Cheese",
          description: "Hambúrguer artesanal duplo, queijo duplo, picles, alface, tomate e molho da casa",
          price: "R$ 42,90"
        },
        {
          name: "Moto Burger",
          description: "Hambúrguer artesanal, queijo provolone, rúcula, tomate seco e maionese de alho",
          price: "R$ 44,90"
        }
      ]
    },
    {
      title: "Porções",
      items: [
        {
          name: "Porção Asfalto Quente",
          description: "Mix de carnes com linguiça defumada, tulipa de frango e costela suína com molhos especiais",
          price: "R$ 65,90"
        },
        {
          name: "Batata Frita Rigon",
          description: "Batata frita crocante com cheddar cremoso, bacon e cebolinha",
          price: "R$ 39,90"
        },
        {
          name: "Anéis de Cebola",
          description: "Anéis de cebola empanados e fritos, acompanha molho especial",
          price: "R$ 32,90"
        }
      ]
    },
    {
      title: "Drinks",
      items: [
        {
          name: "Vintage Bourbon",
          description: "Drink especial com bourbon envelhecido, xarope de maple, bitter artesanal e defumação na hora",
          price: "R$ 38,00"
        },
        {
          name: "Motor Oil",
          description: "Whisky, licor de café, xarope de baunilha e expresso",
          price: "R$ 35,00"
        },
        {
          name: "Road King",
          description: "Gin, tônica, limão siciliano, alecrim e pimenta rosa",
          price: "R$ 32,00"
        }
      ]
    },
    {
      title: "Cervejas",
      items: [
        {
          name: "Chopp Motora",
          description: "Chopp exclusivo do Rigon Motor Bar, produzido artesanalmente com sabor marcante",
          price: "R$ 15,90"
        },
        {
          name: "IPA Estradeira",
          description: "Cerveja artesanal IPA com notas cítricas e amargor equilibrado",
          price: "R$ 25,90"
        },
        {
          name: "Stout Aventura",
          description: "Cerveja escura com notas de café e chocolate",
          price: "R$ 27,90"
        }
      ]
    }
  ];

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
                      key={itemIndex}
                      className="group"
                      whileHover={{ scale: 1.01 }}
                    >
                      <div className="flex justify-between items-baseline">
                        <h3 className="text-xl font-medium group-hover:text-[#F45F0A] transition-colors duration-300">
                          {item.name}
                        </h3>
                        <div className="ml-4 text-lg font-medium text-[#F45F0A] flex-shrink-0">
                          {item.price}
                        </div>
                      </div>
                      
                      <p className="text-zinc-400 text-sm mt-1">
                        {item.description}
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
        </div>
      </div>
      
      <Footer />
    </div>
  );
};

export default MenuPage;
