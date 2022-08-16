import React, {useState} from "react";
import Navbar from "react-bootstrap/Navbar";
import Nav from "react-bootstrap/Nav";
import NavDropdown from "react-bootstrap/NavDropdown";
import {Container, Form} from "react-bootstrap";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {faMoon, faUser} from '@fortawesome/free-solid-svg-icons';
import { useIsAuthenticated, useAuthUser, useSignOut } from 'react-auth-kit';
import { Link } from "react-router-dom";
import { API } from '../../api';

const Navigation = () => {
    const isAuthenticated = useIsAuthenticated();
    const auth = useAuthUser();
    const signOut = useSignOut();
    const api = new API();

    const [isSwitchOn, setIsSwitchOn] = useState(darkmode.inDarkMode);

    const onSwitchAction = () => {
        setIsSwitchOn(!isSwitchOn);
        darkmode.toggleDarkMode();

        if (isSwitchOn) {
            localStorage.removeItem('dark-mode');
        }
        else {
            localStorage.setItem('dark-mode', 'true');
        }
    };

    return (
        <Navbar id="navbar" style={{backgroundColor: "#4287f5"}} expand="lg">
            <Container>
                <Navbar.Brand className="text-white" href="/#">Book Library</Navbar.Brand>
                <Navbar.Toggle aria-controls="basic-navbar-nav" />
                <Navbar.Collapse id="basic-navbar-nav">
                    <Nav className="me-auto">
                        <Nav.Link className="text-white" as={Link} to="/">Home</Nav.Link>
                        <Nav.Link className="text-white me-auto" as={Link} to="/reservations">Reservations</Nav.Link>
                    </Nav>

                    <div className="ms-auto me-2">
                        <Form.Switch
                            className="d-inline me-1"
                            onChange={onSwitchAction}
                            id="custom-switch"
                            checked={isSwitchOn}
                        />
                        <FontAwesomeIcon className="d-inline" icon={faMoon} />
                    </div>

                    { isAuthenticated() ?
                        <NavDropdown title={ auth().name } align="end" id="nav-dropdown">
                            <NavDropdown.Item>Account</NavDropdown.Item>
                            <NavDropdown.Item>Settings</NavDropdown.Item>
                            <NavDropdown.Divider />
                            <NavDropdown.Item onClick={() => { api.logout(); signOut(); }} >Log out</NavDropdown.Item>
                        </NavDropdown>
                        :
                        <NavDropdown title={<FontAwesomeIcon icon={faUser} />} align="end" id="nav-dropdown">
                            <NavDropdown.Item as={Link} to="/login">Log in</NavDropdown.Item>
                            <NavDropdown.Item as={Link} to="/register">Register</NavDropdown.Item>
                            <NavDropdown.Item href="/#">Settings</NavDropdown.Item>
                        </NavDropdown>
                    }
                </Navbar.Collapse>
            </Container>
        </Navbar>
    );
};

export default Navigation;
