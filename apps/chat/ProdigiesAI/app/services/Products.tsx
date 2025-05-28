import { Category } from "@/interfaces/CategoryProps";

export const Products: Category[] = [
    {
        category: 'Management',
        items: [
            { name: 'Ochoa', description: 'Secretary', image: require('@/assets/bots/ochoa.png'), onPressPath: './Content' },
            { name: 'Socrates', description: 'Project Manager', image: require('@/assets/bots/socrates.png'), onPressPath: './Content' },            
        ],
    },
    {
        category: 'Communication',
        items: [
            { name: 'Hemingway', description: 'Copywriter', image: require('@/assets/bots/hemingway.png'), onPressPath: './Content' },
            { name: 'Babel', description: 'Translator', image: require('@/assets/bots/babel.png'), onPressPath: './Content' },
                
        ],
    },
    {
        category: 'Sales',
        items: [
            { name: 'Hippocrates', description: 'Customer Service', image: require('@/assets/bots/hippocrates.png'), onPressPath: './Content' },
            { name: 'Plato', description: 'Clerk', image: require('@/assets/bots/plato.png'), onPressPath: './Content' },
        ],
    },    
    {
        category: 'Financial',
        items: [
            { name: 'Chase', description: 'Accountanting', image: require('@/assets/bots/hamilton.png'), onPressPath: './Content' },
            { name: 'Smith', description: 'Financial Analyst', image: require('@/assets/bots/smith.png'), onPressPath: './Content' },            
        ],
    },
    {
        category: 'Technology',
        items: [
            { name: 'Garvin', description: 'IT Support', image: require('@/assets/bots/garvin.png'), onPressPath: './Content' },
            { name: 'Curie', description: 'Data Research', image: require('@/assets/bots/curie.png'), onPressPath: './Content' },
        ],
    },        
    
    {
        category: 'Supply Chain',
        items: [
            { name: 'Bell', description: 'Inventory', image: require('@/assets/bots/bell.png'), onPressPath: './Content' },
            { name: 'Branson', description: 'Purchases', image: require('@/assets/bots/branson.png'), onPressPath: './Content' },   

        ],
    },       
    {
        category: 'Marketing',
        items: [
            { name: 'Warhol', description: 'Social Media', image: require('@/assets/bots/warhol.png'), onPressPath: './Content' },
            { name: 'Bernbach', description: 'Sales', image: require('@/assets/bots/bernbach.png'), onPressPath: './Content' },
        ],
    },    

    {
        category: 'Bussiness Analyst',
        items: [
            { name: 'Turing', description: 'Bussiness Analyst', image: require('@/assets/bots/turing.png'), onPressPath: './Content' },            
            { name: 'Edison', description: 'Problem Solver', image: require('@/assets/bots/edison.png'), onPressPath: './Content' },
   
        ],
    },
    {
        category: 'Legal',
        items: [
            { name: 'Solon', description: 'Legal Advisor', image: require('@/assets/bots/solon.png'), onPressPath: './Content' },
            { name: 'Helen', description: 'Contract Manager', image: require('@/assets/bots/helen.png'), onPressPath: './Content' },            
            
        ],
    },
    {
        category: 'Human Resources',
        items: [
            { name: 'Carnegie', description: 'HR Manager', image: require('@/assets/bots/carnegie.png'), onPressPath: './Content' },
            { name: 'Marco Polo', description: 'Event Planner', image: require('@/assets/bots/marcopolo.png'), onPressPath: './Content' },            
            

        ],
    },    
 
    {
        category: 'Accesibility',
        items: [
            { name: 'Braile', description: 'Assistive Reading', image: require('@/assets/bots/braille.png'), onPressPath: './Content' },
            { name: 'Erik', description: 'Visual Assistant', image: require('@/assets/bots/erik.png'), onPressPath: './Content' },
        ],
    },    

];

            // { name: 'Li', description: 'Accounts Receivable Clerk', image: require('@/assets/bots/li.png'), onPressPath: './Content' },
            // { name: 'Erik', description: 'Visual Assistant', image: require('@/assets/bots/erik.png'), onPressPath: './Content' },
            // { name: 'Montessori', description: 'Innovative Learning Strategies', image: require('@/assets/bots/montessori.png'), onPressPath: './Content' },
            // { name: 'Ada', description: 'Cybersecurity Awareness', image: require('@/assets/bots/ada.png'), onPressPath: './Content' },
            // { name: 'Chinua', description: 'History Guide', image: require('@/assets/bots/chinua.png'), onPressPath: './Content' },
            // { name: 'Tagore', description: 'Language Tutor', image: require('@/assets/bots/tagore.png'), onPressPath: './Content' },
            // { name: 'Torvalds', description: 'Coding Mentor', image: require('@/assets/bots/torvalds.png'), onPressPath: './Content' },
            // { name: 'Solon', description: 'Legal Guidance', image: require('@/assets/bots/solon.png'), onPressPath: './Content' },
            // { name: 'Demosthenes', description: 'Public Speaking Coach', image: require('@/assets/bots/demosthenes.png'), onPressPath: './Content' },
            // { name: 'Freud', description: 'Mental Wellness and Mindfulness', image: require('@/assets/bots/freud.png'), onPressPath: './Content' },
            // { name: 'Garvin', description: 'Chef Assistant', image: require('@/assets/bots/garvin.png'), onPressPath: './Content' },
            // { name: 'Li', description: 'Fitness Coach', image: require('@/assets/bots/li.png'), onPressPath: './Content' },
            // { name: 'Smith', description: 'Finance Advisor', image: require('@/assets/bots/smith.png'), onPressPath: './Content' },
            // { name: 'Hamilton', description: 'Debt Management', image: require('@/assets/bots/hamilton.png'), onPressPath: './Content' },
            // { name: 'Graham', description: 'Retirement Planning', image: require('@/assets/bots/graham.png'), onPressPath: './Content' },
            // { name: 'Franklin', description: 'Budgeting and Expense Tracking', image: require('@/assets/bots/franklin.png'), onPressPath: './Content' },
            // { name: 'Keynes', description: 'Savings Optimization', image: require('@/assets/bots/keynes.png'), onPressPath: './Content' },
            // { name: 'Braile', description: 'Assistive Reading', image: require('@/assets/bots/braille.png'), onPressPath: './Content' },
            // { name: 'Erik', description: 'Visual Context', image: require('@/assets/bots/erik.png'), onPressPath: './Content' },
            // { name: 'Hippocrates', description: 'Personal Health and Wellness', image: require('@/assets/bots/hippocrates.png'), onPressPath: './Content' },
            // { name: 'Freud', description: 'Mental Wellness and Mindfulness', image: require('@/assets/bots/freud.png'), onPressPath: './Content' },
            // { name: 'Li', description: 'Fitness Coach', image: require('@/assets/bots/li.png'), onPressPath: './Content' },
            // { name: 'Li', description: 'Inventory Manager', image: require('@/assets/bots/li.png'), onPressPath: './Content' },
            // { name: 'Erik', description: 'Visual Assistant', image: require('@/assets/bots/erik.png'), onPressPath: './Content' },
            // { name: 'Hippocrates', description: 'Accounts Payables Clerk', image: require('@/assets/bots/hippocrates.png'), onPressPath: './Content' },
            // { name: 'Freud', description: 'Mental Wellness and Mindfulness', image: require('@/assets/bots/freud.png'), onPressPath: './Content' },
            // { name: 'Garvin', description: 'Chef Assistant', image: require('@/assets/bots/garvin.png'), onPressPath: './Content' },
            // { name: 'Li', description: 'Fitness Coach', image: require('@/assets/bots/li.png'), onPressPath: './Content' },           
            // { name: 'Turing', description: 'Document Analysis', image: require('@/assets/bots/turing.png'), onPressPath: './Content' },
            // { name: 'Curie', description: 'Data Research', image: require('@/assets/bots/curie.png'), onPressPath: './Content' },
            // { name: 'Ogilvy', description: 'Sales Optimization and CRM Management', image: require('@/assets/bots/ogilvy.png'), onPressPath: './Content' },
            // { name: 'Branson', description: 'Feedback Analysis and Response', image: require('@/assets/bots/branson.png'), onPressPath: './Content' },
            // { name: 'Ogilvy', description: 'Sales Optimization and CRM Management', image: require('@/assets/bots/ogilvy.png'), onPressPath: './Content' },
            // { name: 'Warhol', description: 'Social Media Management and Strategy', image: require('@/assets/bots/warhol.png'), onPressPath: './Content' },
            // { name: 'Bernbach', description: 'Marketing Strategy and Planning', image: require('@/assets/bots/bernbach.png'), onPressPath: './Content' },
            // { name: 'Hippocrates', description: 'Personal Health and Wellness', image: require('@/assets/bots/hippocrates.png'), onPressPath: './Content' },
            // { name: 'Freud', description: 'Mental Wellness and Mindfulness', image: require('@/assets/bots/freud.png'), onPressPath: './Content' },
            // { name: 'Garvin', description: 'Chef Assistant', image: require('@/assets/bots/garvin.png'), onPressPath: './Content' },
            
