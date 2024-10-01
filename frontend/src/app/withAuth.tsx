"use client"
import { useEffect } from 'react';
import { useRouter } from 'next/navigation';

const withAuth = (WrappedComponent: React.FC) => {
  return (props: any) => {
    const router = useRouter();
    
    useEffect(() => {
      const token = localStorage.getItem('token');

      if (!token) {
        router.push('/signin');
      } else if (router.pathname === '/signin' && token) {
        router.push('/home');
      }
    }, [router]);

    // Render the wrapped component
    return <WrappedComponent {...props} />;
  };
};

export default withAuth;
