import React, {useEffect, useState} from 'react';
import Navigation from '../Layout/Navigation';
import {Alert, Button, Col, Container, Modal, Row, Spinner, Table} from "react-bootstrap"
import {API} from "../../api";
import store from "../../store";
import {fetchBooks} from "../../Feature/booksSlice";

const ReservationsPage = () => {
    const api = new API();

    const [reservations, setReservations] = useState([]);
    const [pastReservations, setPastReservations] = useState([]);

    const [success, setSuccess] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);

    const fetchReservations = () => {
        setLoading(true);
        api.getReservations(function(response) {
                setReservations(response);
                setLoading(false);
            },
            function(error) {
                console.error(error)
            });
    };

    const fetchReservationsHistory = () => {
        setLoading(true);
        api.getReservationsHistory(function(response) {
                setPastReservations(response);
                setLoading(false);
            },
            function(error) {
                console.error(error)
            });
    };

    useEffect(() => {
        fetchReservations();
    }, []);

    useEffect(() => {
        fetchReservationsHistory();
    }, []);

    function bookReturnCallback() {
        setSuccess('Book returned successfully');
        setError(null);
        store.dispatch(fetchBooks(1));
        fetchReservations();
        fetchReservationsHistory();
    }

    function reservationCancelCallback() {
        setSuccess('Book reservation cancelled successfully');
        setError(null);
        store.dispatch(fetchBooks(1));
        fetchReservations();
        fetchReservationsHistory();
    }

    function reservationFailureCallback() {
        setError('Failed to return a book');
        setSuccess(null);
        store.dispatch(fetchBooks(1));
        fetchReservations();
        fetchReservationsHistory();
    }

    function cancelFailureCallback() {
        setError('Failed to cancel a reservation');
        setSuccess(null);
        store.dispatch(fetchBooks(1));
        fetchReservations();
        fetchReservationsHistory();
    }

    function renderReservationsTable(reservations) {
        return(
            <Row className="mt-4 mb-4 justify-content-center">
                <Col md={8}>
                    <h3>Active Reservations</h3>
                    <hr />
                    <Table striped bordered hover>
                        <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Reservation Start</th>
                            <th>Reservation Expiration</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            { reservations.map((reservation) => {
                                return (
                                    <tr>
                                        <td>{ reservation.book.title }</td>
                                        <td>{ reservation.book.author }</td>
                                        <td>{ reservation.reserved_at }</td>
                                        <td>{ reservation.expiration_date }</td>
                                        <td>
                                            <Button onClick={(event) => api.returnBook( reservation.book.id, bookReturnCallback, reservationFailureCallback)} className="me-1" variant="warning">Return</Button>
                                            <Button onClick={(event) => api.cancelReservation( reservation.book.id, reservationCancelCallback, cancelFailureCallback)} variant="danger">Cancel</Button>
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </Table>
                </Col>
            </Row>
        );
    }

    function renderReservationsHistoryTable(reservations) {
        return(
            <Row className="mt-4 mb-4 justify-content-center">
                <Col md={8}>
                    <h3>Reservations History</h3>
                    <hr />
                    <Table striped bordered hover>
                        <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Reservation Start</th>
                            <th>Reservation Expiration</th>
                            <th>Returned At</th>
                        </tr>
                        </thead>
                        <tbody>
                        { reservations.map((reservation) => {
                            return (
                                <tr>
                                    <td>{ reservation.book.title }</td>
                                    <td>{ reservation.book.author }</td>
                                    <td>{ reservation.reserved_at }</td>
                                    <td>{ reservation.expiration_date }</td>
                                    <td>{ reservation.returned_at }</td>
                                </tr>
                            );
                        })}
                        </tbody>
                    </Table>
                </Col>
            </Row>
        );
    }

    function showLoading() {
        return(
            <Modal show={loading}>
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

    return (
        <>
            { showLoading() }
            <Navigation />
            <Container fluid className='mt-3'>
                <Row className="justify-content-center">
                    <Col md={8}>
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
                    <Col style={{ overflow: "auto" }}>
                        { renderReservationsTable(reservations) }
                    </Col>
                </Row>
                <Row>
                    <Col style={{ overflow: "auto" }}>
                        { renderReservationsHistoryTable(pastReservations) }
                    </Col>
                </Row>
            </Container>
        </>
    );
}

export default ReservationsPage;
