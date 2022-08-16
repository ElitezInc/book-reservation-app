import { Provider } from "react-redux";
import React from "react";
import ReactDOM from "react-dom";
import { Routes, Route, BrowserRouter } from "react-router-dom";
import store from "./store";
import './scss/styles.scss';
import ReservationsPage from "./Components/Pages/ReservationsPage";
import {AuthProvider, RequireAuth, useIsAuthenticated} from "react-auth-kit";
import HomePage from "./Components/Pages/HomePage";
import SignInPage from "./Components/Pages/SignInPage";
import { fetchBooks } from "./Feature/booksSlice";
import RegistrationPage from "./Components/Pages/RegistrationPage";

darkmode.setDarkMode(false);
if (localStorage.getItem('dark-mode') === 'true') {
    darkmode.setDarkMode(true);
}

store.dispatch(fetchBooks(1));

const Routing = () => {
    return (
        <BrowserRouter>
            <Routes>
                <Route exact path="/" element={<HomePage />} />
                <Route path={'/login'} element={<SignInPage/>}/>
                <Route path={'/register'} element={<RegistrationPage/>}/>
                <Route path={'/reservations'} element={
                    <RequireAuth loginPath={'/login'}>
                        <ReservationsPage/>
                    </RequireAuth>
                }/>
            </Routes>
        </BrowserRouter>
    )
}

ReactDOM.render(
    <AuthProvider
        authType = {'localstorage'}
        authName = {'_auth'}
    >
        <Provider store={store}>
            <Routing />
        </Provider>
    </AuthProvider>,
    document.getElementById("app")
);
