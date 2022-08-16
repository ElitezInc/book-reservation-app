import React from 'react';
import Navigation from '../Layout/Navigation';
import { Container } from "react-bootstrap"
import { BooksList } from "../Layout/BooksList";

const HomePage = () => {

    return (
        <>
            <Navigation />
            <Container fluid className='mt-3'>
                <BooksList></BooksList>
            </Container>
        </>
    );
}

export default HomePage;
