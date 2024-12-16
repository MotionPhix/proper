export interface Links {
  url: string;
  label: string;
  active: boolean;
}

export interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  email_verified_at: string;
}

export interface Company {
  id: number;
  name: string;
}

export interface Interaction {
  id: number;
  type: string;
  topic: string;
  contact_id: number;
  user_id: number;
  interacted_on: Date|string;
  interaction_date: Date|string;
}

export interface Phone {
  id: number;
  number: string;
  type: string;
}

export interface Board {
  id: number;
  name: string;
  user_id?: number;
  project_id?: number;
  tasks?: Task[]
}

export interface Task {
  id: number;
  title: string;
  description?: string;
  cost?: number;
  position?: number;
  user_id?: number;
  project_id?: number;
  board_id?: number;
  status?: string;
  start_date?: string|date;
  end_date?: string|date;
  user?: User;
  board?: Board;
  project: Project;
}

export interface Contact {
  id?: number;
  idx?: number;
  full_name?: string;
  first_name: string;
  last_name: string;
  status?: string;
  email?: string;
  user?: User;
  user_id?: number;
  users?: User[];
  phones?: Phone[];
  company?: Company;
  company_id?: number;
  companies?: Company[];
  interactions?: Interaction[];
  revenue?: string;
}

export interface Project {
  id?: number;
  pid?: string;
  name?: string;
  description?: string;
  status?: string;
  company_id?: number|null;
  contact_id?: number|null;
  progress?: number;
  deadline?: date|string;
  contact?: Contact;
  tasks?: Task[];
  users?: User[];
  company?: Company;
  boards?: Board[]
}

export interface ContactApiResponse {
  data: Contact[];
  total?: number;
  links?: Links[];
  from?: number;
  to?: number;
}

export interface ProjectApiResponse {
  data: Project[];
  total?: number;
  links?: Links[];
  from?: number;
  to?: number;
}


export interface TaskApiResponse {
  data: Task[];
  total?: number;
  links?: Links[];
  from?: number;
  to?: number;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User;
    avatar: string,
    role: string
  };
};
