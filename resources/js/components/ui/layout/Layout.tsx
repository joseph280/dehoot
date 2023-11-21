import React, { useEffect, useState } from 'react';
import { AnimatePresence } from 'framer-motion';
import Footer from '../footer/Footer';
import { Navbar } from '../navbar/Navbar';
import { StakedAssetModal } from '@/components/modals/StakedAssetModal';
import { Asset } from '@/types/schemas';

interface LayoutProps {
  children: React.ReactNode;
}

export function Layout({ children }: LayoutProps) {
  const [stakedAsset, setStakedAsset] = useState<Asset | undefined>();
  const [openStakedAssetModal, setOpenStakedAssetModal] = useState<boolean>(false);

  useEffect(() => {
    document.addEventListener('selectStakedAsset', (ev: Event) => {
      const event = ev as CustomEvent;
      setStakedAsset(event.detail.asset);
      setOpenStakedAssetModal(true);
    });

    return () => document.removeEventListener('selectStakedAsset', () => {});
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  return (
    <div className="relative h-full min-h-screen w-full bg-gray-900">
      <Navbar />
      {children}
      {openStakedAssetModal && stakedAsset && (
        <AnimatePresence>
          <StakedAssetModal
            onClose={() => setOpenStakedAssetModal(false)}
            stakedAsset={stakedAsset}
          />
        </AnimatePresence>
      )}
      <Footer />
    </div>
  );
}
