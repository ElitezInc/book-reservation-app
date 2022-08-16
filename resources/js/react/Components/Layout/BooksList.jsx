import { useSelector } from "react-redux";
import React, { useState } from "react";
import store from "../../store";
import { fetchBooks } from "../../Feature/booksSlice";
import {Button, Card, Col, Container, Row, Form, Image, Alert, Modal, Spinner} from "react-bootstrap";
import Pagination from './PaginationComponent'
import { faGrip, faList } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { LazyLoadImage } from 'react-lazy-load-image-component';
import PlaceholderImage from '../../Images/open-book.svg'
import {useIsAuthenticated} from "react-auth-kit";
import warning from "react-redux/es/utils/warning";
import {API} from "../../api";

export function BooksList() {
    const isAuthenticated = useIsAuthenticated();
    const api = new API();

    const books = useSelector((state) => state.books.data);
    const totalBooks = useSelector((state) => state.books.total_items);
    const currentPage = useSelector((state) => state.books.current_page);
    const booksLoading = useSelector((state) => state.books.loading);

    const [success, setSuccess] = useState(null);
    const [error, setError] = useState(null);

    const booksPerPage = 10;
    const imagesPath = 'storage/images/';

    const [asList, setAsList] = useState(false);

    const pageNavigation = (pageNumber) => {
        if (pageNumber !== 0) {
            store.dispatch(fetchBooks(pageNumber));
        }
    }

    function renderBooksGrid(books) {
        return(
            <Row className="mt-4 mb-4">
                {books.map((book) => {
                    return (
                        <Col xs={12} sm={6} md={4} lg={3} className='mb-4'>
                            <Card>
                                {
                                    book.image_name ?
                                        <LazyLoadImage
                                            className='card-img-top'
                                            alt={imagesPath + book.image_name}
                                            src={imagesPath + book.image_name}
                                            height={imagesPath + book.image_name.height}
                                            width={imagesPath + book.image_name.width}
                                        />
                                        :
                                        <Image className="p-3" src={PlaceholderImage} rounded={true}></Image>
                                }
                                <Card.Body>
                                    <Card.Title>{ book.title }</Card.Title>
                                    <Card.Text>
                                        {
                                            book.description && book.description.length > 250 ?
                                                book.description.substring(0, 250) + '...'
                                                :
                                                book.description
                                        }
                                    </Card.Text>
                                </Card.Body>
                                <Card.Footer className="d-flex justify-content-end">
                                    <Button className="me-1" variant="primary">View</Button>
                                    {
                                        isAuthenticated() ?
                                        <Button onClick={(event) => api.reservate(book.id, reservationCallback, failureCallback)} variant="success">Reservate</Button>
                                        : <></>
                                    }
                                </Card.Footer>
                            </Card>
                        </Col>
                    );
                })}
            </Row>
        );
    }

    function renderBooksList(books) {
        return(
            <Row className="mt-4 mb-4">
                {books.map((book) => {
                    return (
                        <Col xs={12} className='mb-4'>
                            <Card>
                                <Card.Body>
                                    <Row>
                                        <Col sm={12} md={4} lg={2}>
                                            {
                                                book.image_name ?
                                                <LazyLoadImage
                                                    className="d-inline w-100"
                                                    alt={imagesPath + book.image_name}
                                                    src={imagesPath + book.image_name}
                                                    height={imagesPath + book.image_name.height}
                                                    width={imagesPath + book.image_name.width}
                                                />
                                                :
                                                <Image src={PlaceholderImage} className="d-inline w-100" rounded={true}></Image>
                                            }
                                        </Col>
                                        <Col sm={12} md={8} lg={10}>
                                            <h6>{ book.title }</h6>
                                            <p>{ book.description }</p>
                                        </Col>
                                    </Row>
                                </Card.Body>
                                <Card.Footer className="d-flex justify-content-end">
                                    <Button className="me-1" variant="primary">View</Button>
                                    {
                                        isAuthenticated() ?
                                        <Button onClick={(event) => api.reservate(book.id, reservationCallback, failureCallback)} variant="success">Reservate</Button>
                                        : <></>
                                    }
                                </Card.Footer>
                            </Card>
                        </Col>
                    );
                })}
            </Row>
        );
    }

    function showLoading() {
        return(
            <Modal show={booksLoading}>
                <Modal.Header>
                    <Modal.Title>Loading...</Modal.Title>
                </Modal.Header>
                <Modal.Body className="d-flex justify-content-center">
                    <Spinner className="p-3" animation="border" role="status" style={{ height: "100px", width: "100px" }}>
                        <span className="visually-hidden">Loading...</span>
                    </Spinner>
                </Modal.Body>
            </Modal>
        );
    }

    function reservationCallback() {
        setSuccess('Book reservated successfully');
        setError(null);
        store.dispatch(fetchBooks(currentPage));
    }

    function failureCallback() {
        setError('Failed to reservate a book');
        setSuccess(null);
        store.dispatch(fetchBooks(currentPage));
    }

    return (
        <Container>
            { showLoading() }
            <Row>
                <Col>
                    {
                        success ?
                        <Alert variant={'success'}>
                            {success}
                        </Alert>
                        : <></>
                    }
                    {
                        error ?
                        <Alert variant={'danger'}>
                            {error}
                        </Alert>
                        : <></>
                    }
                </Col>
            </Row>
            <Row>
                <Col>
                    {
                        !isAuthenticated() ?
                        <Alert className="d-flex justify-content-center" variant={'warning'}>
                            Please Log in in order to reservate books
                        </Alert>
                        :
                        <></>
                    }
                </Col>
            </Row>
            <Row>
                <Col className="d-flex justify-content-between mb-3">
                    <div className="d-flex">
                        <Button onClick={(e) => { setAsList(false) }} className="me-1" variant={ asList ? 'secondary' : 'light'}>
                            <FontAwesomeIcon icon={faGrip} />
                        </Button>
                        <Button onClick={(e) => { setAsList(true) }} variant={ asList ? 'light' : 'secondary'}>
                            <FontAwesomeIcon icon={faList} />
                        </Button>
                    </div>
                    <div className="d-flex">
                        <span className="pt-2 me-3">Search</span>
                        <Form.Control className="d-inline" style={{ width:'200px'}} type="text" placeholder="Book Title" />
                    </div>
                </Col>
            </Row>
            <Row>
                <hr/>
            </Row>
                { asList ? renderBooksList(books) : renderBooksGrid(books) }
            <Row>
                <hr/>
            </Row>
            <Pagination
                itemsCount={totalBooks}
                itemsPerPage={booksPerPage}
                currentPage={currentPage}
                setCurrentPage={pageNavigation}
                alwaysShown={false}
            />
        </Container>
    );
}
