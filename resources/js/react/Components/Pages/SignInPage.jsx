import React, {useState} from "react";
import { API } from '../../api';
import { useNavigate } from "react-router-dom";
import {Alert, Button, Card, Col, Container, Form, Row} from "react-bootstrap";
import Navigation from "../Layout/Navigation";

const SignInPage = () => {
    const [formData, setFormData] = React.useState({username: '', password: ''});
    const [error, setError] = useState(null);

    const navigate = useNavigate();
    const api = new API();

    const onSubmit = (e) => {
        e.preventDefault();

        api.logIn(formData.email, formData.password,
            function() {
                navigate("/");
            },
            function(error) {
                console.error(error);
                setError("Authentication error");
            }
        );
    }

    return (
        <>
            <Navigation />
            <Container>
                <Row>
                    <Col>
                        {
                            error ?
                            <Alert className="mt-3" variant={'danger'}>
                                {error}
                            </Alert>
                            : <></>
                        }
                    </Col>
                </Row>
                <Row className="justify-content-center">
                    <Col md={8}>
                        <Card className="mt-3">
                            <Card.Header>Login</Card.Header>
                            <Card.Body>
                                <Form onSubmit={onSubmit}>
                                    <Form.Group className="mb-3" controlId="formBasicEmail">
                                        <Form.Label>Email address</Form.Label>
                                        <Form.Control onChange={(e)=>setFormData({...formData, email: e.target.value})} type="email" placeholder="Enter email" />
                                    </Form.Group>

                                    <Form.Group className="mb-3" controlId="formBasicPassword">
                                        <Form.Label>Password</Form.Label>
                                        <Form.Control onChange={(e)=>setFormData({...formData, password: e.target.value})} type="password" placeholder="Password" />
                                    </Form.Group>

                                    <Button onClick={(e) => navigate('/register')} className="me-1" variant="success">Register</Button>
                                    <Button variant="primary" type="submit">Log In</Button>
                                </Form>
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    )
}

export default SignInPage;
