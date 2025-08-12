import React from 'react';

type MenuItemProps = {
  name: string;
  description: string;
  price: string;
  category: string;
};

const MenuItem: React.FC<MenuItemProps> = ({ name, description, price, category }) => (
  <div className="border-b border-zinc-800 py-4 last:border-b-0">
    <div className="flex justify-between items-baseline">
      <h4 className="text-lg font-semibold">{name}</h4>
      <span className="text-[#F45F0A] font-medium">{price}</span>
    </div>
    <p className="text-gray-400 text-sm mt-1">{description}</p>
  </div>
);

const MenuHighlights: React.FC = () => {
  const menuItems = [
    {
      name: "Road Burger",
      description: "Hambúrguer artesanal, queijo cheddar, bacon crocante, cebola caramelizada e molho especial da casa",
      price: "R$ 39,90",
      category: "Hambúrgueres"
    },
    {
      name: "Porção Asfalto Quente",
      description: "Mix de carnes com linguiça defumada, tulipa de frango e costela suína com molhos especiais",
      price: "R$ 65,90",
      category: "Porções"
    },
    {
      name: "Vintage Bourbon",
      description: "Drink especial com bourbon envelhecido, xarope de maple, bitter artesanal e defumação na hora",
      price: "R$ 38,00",
      category: "Drinks"
    },
    {
      name: "Chopp Motora",
      description: "Chopp exclusivo do Rigon Motor Bar, produzido artesanalmente com sabor marcante",
      price: "R$ 15,90",
      category: "Bebidas"
    }
  ];

  return (
    <section className="bg-zinc-900 py-16 md:py-24" id="cardapio">
      <div className="container mx-auto px-4">
        <div className="flex flex-col items-center mb-12">
          <span className="text-[#F45F0A] font-semibold mb-2">SABORES EXCLUSIVOS</span>
          <h2 className="text-3xl md:text-4xl font-bold mb-6 text-center text-white">
            Destaques do Cardápio
          </h2>
          <div className="h-1 w-40 bg-[#F45F0A] mx-auto"></div>
        </div>
        
        <div className="grid md:grid-cols-2 gap-12">
          <div>
            <div className="h-full rounded-lg overflow-hidden bg-black/30 backdrop-blur-sm p-8 border border-zinc-800 hover:border-[#F45F0A]/30 transition-all duration-300">
              {menuItems.slice(0, 2).map((item, index) => (
                <MenuItem
                  key={index}
                  name={item.name}
                  description={item.description}
                  price={item.price}
                  category={item.category}
                />
              ))}
            </div>
          </div>
          <div>
            <div className="h-full rounded-lg overflow-hidden bg-black/30 backdrop-blur-sm p-8 border border-zinc-800 hover:border-[#F45F0A]/30 transition-all duration-300">
              {menuItems.slice(2, 4).map((item, index) => (
                <MenuItem
                  key={index}
                  name={item.name}
                  description={item.description}
                  price={item.price}
                  category={item.category}
                />
              ))}
            </div>
          </div>
        </div>
        
        <div className="text-center mt-12">
          <a href="/cardapio" className="inline-block px-8 py-3 bg-[#F45F0A] hover:bg-[#d54d02] text-white font-bold rounded-md transition-all duration-300 hover:scale-105">Ver cardápio completo</a>
        </div>
      </div>
    </section>
  );
};

export default MenuHighlights;