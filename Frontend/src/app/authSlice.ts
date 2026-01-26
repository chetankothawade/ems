import { createSlice } from "@reduxjs/toolkit";

const slice = createSlice({
  name: "auth",
  initialState: { role: "student" },
  reducers: {
    setRole: (state, action) => {
      state.role = action.payload;
    }
  }
});

export const { setRole } = slice.actions;
export default slice.reducer;
