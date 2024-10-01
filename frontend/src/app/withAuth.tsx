"use client"
import { useEffect } from 'react';
import { useRouter , usePathname } from 'next/navigation';

const withAuth = (WrappedComponent: React.FC) => {
  return (props: any) => {
    const router = useRouter();
    const pathname = usePathname();
    
    useEffect(() => {
      const token = localStorage.getItem('token');

      if (!token) {
        router.push('/signin');
      } else if (pathname === '/signin' && token) {
        router.push('/home');
      }
    }, [router]);

    // Render the wrapped component
    return <WrappedComponent {...props} />;
  };
};

export default withAuth;
