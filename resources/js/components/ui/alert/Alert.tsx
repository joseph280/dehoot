import React, { Fragment, useEffect, useRef, useState } from 'react';
import { Transition } from '@headlessui/react';
import { classNames } from '@/support/Global';

export type AlertTypes = 'success' | 'information' | 'warning' | 'danger';

interface AlertProps {
  message: string;
  status: AlertTypes;
  handleClose: () => void;
}

const styles = {
  success: {
    gradient: 'from-green-600 to-green-900',
    shadow: 'shadow-green-600',
    textColor: 'text-slate-50',
  },
  information: {
    gradient: 'from-gray-600 to-gray-900',
    shadow: 'shadow-gray-600',
    textColor: 'text-slate-50',
  },
  warning: {
    gradient: 'from-yellow-600 to-yellow-900',
    shadow: 'shadow-yellow-600',
    textColor: 'text-slate-50',
  },
  danger: {
    gradient: 'from-red-600 to-red-900',
    shadow: 'shadow-red-600',
    textColor: 'text-slate-50',
  },
};

export function Alert({ message, status, handleClose }: AlertProps) {
  const [show, setShow] = useState(false);

  const timeout = useRef<NodeJS.Timeout>();

  useEffect(() => {
    if (message && message !== '') {
      setShow(true);

      timeout.current = setTimeout(() => {
        setShow(false);
      }, 5000);
    }

    return () => (timeout.current ? clearTimeout(timeout.current) : undefined);
  }, [message]);

  return (
    <>
      {/* Global notification live region, render this permanently at the end of the document */}
      <div
        aria-live="assertive"
        className="pointer-events-none fixed inset-0 z-50 flex items-start px-4 py-6 font-Poppins sm:p-6"
      >
        <div className="flex h-full w-full items-center justify-center">
          {/* Notification panel, dynamically insert this into the live region when it needs to be displayed */}
          <Transition
            show={show}
            as={Fragment}
            enter="transform ease-out duration-300 transition"
            enterFrom="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enterTo="translate-y-0 opacity-100 sm:translate-x-0"
            leave="transition ease-in duration-75"
            leaveFrom="opacity-100"
            leaveTo="opacity-0"
            afterLeave={() => handleClose()}
          >
            <div
              className={classNames(
                styles[status].shadow,
                'pointer-events-auto w-auto overflow-hidden rounded-lg shadow-center-2xl',
              )}
            >
              <div
                className={classNames(
                  styles[status].gradient,
                  'bg-gradient-to-r px-[1px] py-[1px] shadow-center-2xl',
                  'rounded-lg p-4',
                )}
              >
                <div className="flex flex-col items-center justify-start space-y-3 rounded-lg bg-gray-800 py-4 pl-3 pr-4">
                  <div className="mx-3">
                    <h3 className="text-slate-50 text-lg font-bold">
                      {status === 'information' && <>Information</>}
                      {status === 'success' && <>Great</>}
                      {status === 'danger' && <>There was an error</>}                      
                      {status === 'warning' && <>Warning</>}
                    </h3>
                  </div>
                  <div className="mx-3 text-center">
                    <h3
                      className={classNames(
                        styles[status].textColor,
                        'text-sm',
                      )}
                    >
                      {message}
                    </h3>
                  </div>
                  <div
                    className="cursor-pointer rounded-lg bg-gray-900 px-4 py-2 text-slate-200 hover:text-slate-100"
                    onClick={() => setShow(false)}
                  >
                    <span className="text-sm font-medium">Dismiss</span>
                  </div>
                </div>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </>
  );
}
