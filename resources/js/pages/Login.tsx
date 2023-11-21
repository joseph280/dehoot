import React, { useEffect } from 'react';
import { Inertia } from '@inertiajs/inertia';
import * as waxjs from '@waxio/waxjs/dist';
import { Head } from '@inertiajs/inertia-react';
import { Button } from '@/components/ui/button/Button';
import { useAppState } from '@/contexts/stateContext';

export default function Login() {
  const { state, dispatch } = useAppState();

  const signIn = async () => {
    const wax = new waxjs.WaxJS({ rpcEndpoint: 'https://wax.greymass.com' });
    const isAutoLoginAvailable = await wax.isAutoLoginAvailable();

    if (!isAutoLoginAvailable) {
      await wax.login();
    }

    if (wax.user) {
      Inertia.post('/signin', {
        account_id: wax.userAccount,
        key_1: wax.user?.keys[0],
        key_2: wax.user?.keys[1],
      });
    }
  };

  useEffect(
    () =>
      Inertia.on('start', () => {
        dispatch({
          type: 'processing',
        });
      }),
    [dispatch],
  );

  useEffect(
    () =>
      Inertia.on('finish', () => {
        dispatch({
          type: 'reset',
        });
      }),
    [dispatch],
  );

  return (
    <div className="relative h-screen w-full">
      <Head title="Login" />

      <div
        className="absolute top-0 z-20 h-60 w-full bg-cover opacity-40 blur-sm lg:hidden"
        style={{
          backgroundImage: 'url("/assets/images/bg-city2.jpg")',
        }}
      />

      <div className="absolute top-0 z-30 flex h-full w-full flex-col items-center justify-start lg:w-1/2 lg:justify-center">
        <div className="flex h-60 w-full items-center justify-center">
          <img
            className="w-64 max-w-xs sm:w-auto lg:max-w-sm xl:max-w-md"
            src="/assets/logo/color.png"
            alt="DeHoot Valley Logo"
          />
        </div>

        <div className="mt-16 w-full self-center px-5 sm:max-w-lg lg:mt-7 lg:mb-20 lg:max-w-md">
          <div className="flex justify-center">
            <Button
              processing={state.processing}
              onClick={signIn}
              className="w-full rounded-lg text-white border-l-2 border-t-2 border-r-2 border-b-4 border-black bg-gradient-to-l from-teal-300 to-sky-400 py-1.5 capitalize shadow-lg outline-none transition-transform hover:-translate-y-1 hover:from-teal-400 hover:to-sky-500 lg:py-3"
              spanClassName="select-none font-Poppins text-lg font-bold lg:text-xl"
              text="Log In"
            />
          </div>
          <p className="mt-3 select-none text-center text-sm font-bold text-white lg:text-base lg:font-extrabold">
            Press the button to log in with your wallet
          </p>
        </div>
      </div>

      <div
        className="absolute inset-x-1/2 z-20 hidden h-full w-1/2 bg-cover bg-right bg-no-repeat lg:block"
        style={{
          backgroundImage: 'url("/assets/images/bg-city-vertical.png")',
        }}
      />

      <div className="absolute bottom-0 z-50 flex h-20 w-full items-center justify-end px-4 sm:px-10">
        {/* <div className="my-6 flex">
          <div className="">
            <a
              className="font-IBMPlexMono text-sm font-bold text-slate-50 lg:text-base"
              href="#privacy"
            >
              Privacy Policy
            </a>
          </div>
          <div className="ml-5 lg:ml-12">
            <a
              className="font-IBMPlexMono text-sm font-bold text-slate-50 lg:text-base"
              href="#term"
            >
              Term of Service
            </a>
          </div>
        </div> */}
        <img className="w-20" src="/assets/logo/color-isologo.png" alt=" " />
      </div>

      <div className="absolute bottom-0 z-40 h-20 w-full bg-gray-800 lg:opacity-90" />
      <div className="absolute z-10 h-full w-full bg-gray-900 lg:w-1/2" />
      <div className="absolute z-0 hidden h-full w-full bg-sky-400 lg:block" />
    </div>
  );
}
