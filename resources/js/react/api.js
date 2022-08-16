import axios from 'axios';
import { useSignIn } from 'react-auth-kit';
import qs from 'qs';

export class API {
    signIn = useSignIn();
    token = document.head.querySelector('meta[name="csrf-token"]').content;
    bearerToken = localStorage.getItem("_auth");

    logIn(email, password, onSuccess, onError) {
        axios.post('/api/login', qs.stringify({
            email: email,
            password: password
        }), { headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "Accept": "application/json",
            "X-CSRF-TOKEN": this.token
        } })
        .then((res) => {
            console.log(res);

            if(res.status === 200) {
                if(this.signIn({
                    token: res.data.authorisation.token,
                    tokenType: "Bearer",
                    expiresIn: 24 * 60 * 7,
                    authState: { name: res.data.user.name, email: res.data.user.email }
                }))
                {
                    onSuccess();
                }
                else {
                    onError("Failed to sign in");
                }
            }
        })
        .catch(err => {
            onError(err);
        });
    }

    logout() {
        axios.post('/api/logout', { }, { headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "Accept": "application/json",
            "X-CSRF-TOKEN": this.token,
            "Authorization" : `Bearer ${this.bearerToken}`
        } })
        .then((res) => {
            console.log(res);
        });
    }

    register(name, email, password, onSuccess, onError) {
        axios.post('/api/register', qs.stringify({
            name: name,
            email: email,
            password: password
        }), { headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "Accept": "application/json",
                "X-CSRF-TOKEN": this.token
            } })
            .then((res) => {
                console.log(res);

                if(res.status === 200) {
                    if(this.signIn({
                        token: res.data.authorisation.token,
                        tokenType: "Bearer",
                        expiresIn: 24 * 60 * 7,
                        authState: { name: res.data.user.name, email: res.data.user.email }
                    }))
                    {
                        onSuccess();
                    }
                    else {
                        onError("Failed to register");
                    }
                }
            })
            .catch(err => {
                onError(err);
            });
    }

    getReservations(onSuccess, onError) {
        fetch('api/reservations', {
            method: 'get',
            headers: new Headers({
                "Content-Type": "application/x-www-form-urlencoded",
                "Accept": "application/json",
                "X-CSRF-TOKEN": this.token,
                "Authorization": `Bearer ${this.bearerToken}`
            }),
        })
        .then(result => {
            if(result.status === 200) {
                return result.json();
            }
        })
        .then(result => onSuccess(result))
        .catch(error => {
            onError(error);
        });
    }

    getReservationsHistory(onSuccess, onError) {
        fetch('api/reservations_histories', {
            method: 'get',
            headers: new Headers({
                "Content-Type": "application/x-www-form-urlencoded",
                "Accept": "application/json",
                "X-CSRF-TOKEN": this.token,
                "Authorization": `Bearer ${this.bearerToken}`
            }),
        })
            .then(result => {
                if(result.status === 200) {
                    return result.json();
                }
            })
            .then(result => onSuccess(result))
            .catch(error => {
                onError(error);
            });
    }

    reservate(bookId, onSuccess, onError) {
        fetch('api/reservate/' + bookId, {
            method: 'post',
            headers: new Headers({
                "Content-Type": "application/x-www-form-urlencoded",
                "Accept": "application/json",
                "X-CSRF-TOKEN": this.token,
                "Authorization": `Bearer ${this.bearerToken}`
            }),
        })
        .then(result => {
            if(result.status === 200) {
                onSuccess()
            }
        })
        .catch(error => {
            onError(error);
        });
    }

    returnBook(bookId, onSuccess, onError) {
        fetch('api/return/' + bookId, {
            method: 'post',
            headers: new Headers({
                "Content-Type": "application/x-www-form-urlencoded",
                "Accept": "application/json",
                "X-CSRF-TOKEN": this.token,
                "Authorization": `Bearer ${this.bearerToken}`
            }),
        })
            .then(result => {
                if(result.status === 200) {
                    onSuccess()
                }
            })
            .catch(error => {
                onError(error);
            });
    }

    cancelReservation(bookId, onSuccess, onError) {
        fetch('api/cancel/' + bookId, {
            method: 'post',
            headers: new Headers({
                "Content-Type": "application/x-www-form-urlencoded",
                "Accept": "application/json",
                "X-CSRF-TOKEN": this.token,
                "Authorization": `Bearer ${this.bearerToken}`
            }),
        })
            .then(result => {
                if(result.status === 200) {
                    onSuccess()
                }
            })
            .catch(error => {
                onError(error);
            });
    }
}
