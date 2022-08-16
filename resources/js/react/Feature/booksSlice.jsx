import {createAsyncThunk, createSlice} from "@reduxjs/toolkit";

export const fetchBooks = createAsyncThunk("fetchBooks", async (page) => {
    const response = await fetch("api/books?page=" + page);
    return await response.json();
});

const booksSlice = createSlice({
    name: "books",
    initialState: {
        data: [],
        current_page: 1,
        total_items: 0,
        loading: false,
    },
    reducers: {},
    extraReducers: {
        [fetchBooks.pending]: (state, action) => {
            state.loading = true;
        },
        [fetchBooks.fulfilled]: (state, { payload }) => {
            state.loading = false;
            state.data = payload.data;
            state.current_page = payload.current_page;
            state.total_items = payload.total;
        },
        [fetchBooks.rejected]: (state, action) => {
            state.loading = false;
        },
    },
});

export default booksSlice.reducer;
