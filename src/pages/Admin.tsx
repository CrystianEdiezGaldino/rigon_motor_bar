import React, { useState, useEffect } from 'react';
import AdminLogin from '../components/AdminLogin';
import AdminPanel from '../components/AdminPanel';
import { useToast } from '../hooks/use-toast';

const Admin: React.FC = () => {
  const [user, setUser] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const { toast } = useToast();

  // Verificar se usuário está logado
  useEffect(() => {
    checkAuthStatus();
  }, []);

  const checkAuthStatus = async () => {
    try {
      const response = await fetch('/api/endpoints/auth.php');
      if (response.ok) {
        const data = await response.json();
        if (data.logged_in) {
          setUser(data.user);
        }
      }
    } catch (error) {
      console.error('Erro ao verificar status de autenticação:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleLoginSuccess = (userData: any) => {
    setUser(userData);
    toast({
      title: "Bem-vindo!",
      description: `Olá ${userData.nome}!`,
    });
  };

  const handleLogout = () => {
    setUser(null);
    toast({
      title: "Logout realizado",
      description: "Você saiu do sistema",
    });
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-gray-900 mx-auto"></div>
          <p className="mt-4 text-lg">Carregando...</p>
        </div>
      </div>
    );
  }

  return (
    <div>
      {user ? (
        <AdminPanel user={user} onLogout={handleLogout} />
      ) : (
        <AdminLogin onLoginSuccess={handleLoginSuccess} />
      )}
    </div>
  );
};

export default Admin;
