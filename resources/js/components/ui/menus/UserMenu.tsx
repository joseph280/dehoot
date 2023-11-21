import React, { Fragment } from 'react';
import { Inertia } from '@inertiajs/inertia';
import { Transition, Menu } from '@headlessui/react';
import { classNames } from '@/support/Global';

interface UserMenuProps {
  className?: string;
}

export default function UserMenu({ className }: UserMenuProps) {
  const logout = async () => Inertia.post('/logout');

  return (
    <Menu
      as="div"
      className={classNames(
        className ?? '',
        'relative z-10 rounded-md bg-gradient-to-r from-dehoot-purple-500 to-dehoot-blue-500 px-[1px] pt-[1px] pb-[3px]',
        'transition-all active:scale-110',
      )}
    >
      <Menu.Button className="flex h-full w-full items-center justify-center rounded-md bg-gray-800 px-2">
        <img
          src="/assets/icons/avatar.png"
          className="h-8 w-8 text-white"
          alt=" "
        />
      </Menu.Button>
      <Transition
        as={Fragment}
        enter="transition ease-out duration-100"
        enterFrom="transform opacity-0 scale-95"
        enterTo="transform opacity-100 scale-100"
        leave="transition ease-in duration-75"
        leaveFrom="transform opacity-100 scale-100"
        leaveTo="transform opacity-0 scale-95"
      >
        <Menu.Items className="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-600 rounded-lg border border-gray-600 bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
          <div className="px-1 py-1 ">
            <Menu.Item>
              <button
                disabled
                className="group flex w-full items-center justify-center rounded-md px-2 py-2 text-sm"
              >
                <div className="opacity-40">
                  <p className="text-slate-50">Buy create</p>
                  <span className="text-sm text-[#0CFBD4]">coming soon!</span>
                </div>
              </button>
            </Menu.Item>
          </div>
          <div className="px-1 py-1 ">
            <Menu.Item>
              <button
                onClick={logout}
                className="group flex w-full items-center justify-center rounded-md px-2 py-2 text-sm"
              >
                <div>
                  <p className="text-slate-50">Logout</p>
                </div>
              </button>
            </Menu.Item>
          </div>
        </Menu.Items>
      </Transition>
    </Menu>
  );
}
