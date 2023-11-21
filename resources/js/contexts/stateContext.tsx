import * as React from 'react';
import { Game } from '@/game/game';
import { Observable } from '@/support/Observable';

type Action =
  | { type: 'set'; payload: State }
  | { type: 'reset' }
  | { type: 'processing' };
type Dispatch = (action: Action) => void;
type State = {
  message?: string;
  status?: string;
  processing?: boolean;
  game?: Game;
  observable?: Observable;
};
type StateProviderProps = { children: React.ReactNode };

const StateContext = React.createContext<
{ state: State; dispatch: Dispatch } | undefined
>(undefined);

function stateReducer(state: State, action: Action) {
  switch (action.type) {
    case 'set': {
      return {
        message: action.payload.message ?? state.message,
        status: action.payload.status ?? state.status,
        processing: action.payload.processing ?? false,
        game: action.payload.game ?? state.game,
        observable: state.observable,
      };
    }
    case 'reset': {
      return {
        message: '',
        status: 'information',
        processing: false,
        game: state.game ?? undefined,
        observable: state.observable,
      };
    }
    case 'processing': {
      const data = { ...state };
      data.processing = true;
      return data;
    }
    default: {
      throw new Error(`Unhandled action type: ${action}`);
    }
  }
}

function StateProvider({ children }: StateProviderProps) {
  const [state, dispatch] = React.useReducer(stateReducer, {
    message: '',
    status: 'information',
    processing: false,
    observable: new Observable(),
  });

  const value = React.useMemo(
    () => ({
      state,
      dispatch,
    }),
    [state],
  );

  return (
    <StateContext.Provider value={value}>{children}</StateContext.Provider>
  );
}

function useAppState() {
  const context = React.useContext(StateContext);
  if (context === undefined) {
    throw new Error('useState must be used within a StateProvider');
  }
  return context;
}

export { StateProvider, useAppState };
