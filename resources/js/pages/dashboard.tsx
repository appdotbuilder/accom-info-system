import React from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

interface Props {
    auth?: {
        user: {
            id: number;
            name: string;
            email: string;
            role: string;
        };
    };
    [key: string]: unknown;
}

export default function Dashboard({ auth }: Props) {
    // If no auth data, redirect to login
    if (!auth?.user) {
        router.visit('/login');
        return null;
    }

    const user = auth.user;

    const getDashboardContent = () => {
        switch (user.role) {
            case 'admin':
                return (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">👨‍💼</div>
                        <h2 className="text-2xl font-bold mb-4">Admin Dashboard</h2>
                        <p className="text-gray-600 mb-6">System management and oversight</p>
                        <div className="flex justify-center gap-4">
                            <Button onClick={() => router.visit('/properties')}>
                                🏠 Manage Properties
                            </Button>
                            <Button variant="outline" onClick={() => router.visit('/users')}>
                                👥 Manage Users
                            </Button>
                        </div>
                    </div>
                );

            case 'accommodation_owner':
                return (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">🏠</div>
                        <h2 className="text-2xl font-bold mb-4">Property Owner Dashboard</h2>
                        <p className="text-gray-600 mb-6">Manage your properties and bookings</p>
                        <div className="flex justify-center gap-4">
                            <Button onClick={() => router.visit('/properties/create')}>
                                ➕ Add Property
                            </Button>
                            <Button variant="outline" onClick={() => router.visit('/bookings')}>
                                📅 View Bookings
                            </Button>
                        </div>
                    </div>
                );

            case 'customer':
                return (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">🧳</div>
                        <h2 className="text-2xl font-bold mb-4">Welcome Back, Traveler!</h2>
                        <p className="text-gray-600 mb-6">Plan your next amazing stay</p>
                        <div className="flex justify-center gap-4">
                            <Button onClick={() => router.visit('/properties')}>
                                🔍 Browse Properties
                            </Button>
                            <Button variant="outline" onClick={() => router.visit('/bookings')}>
                                📋 My Bookings
                            </Button>
                        </div>
                    </div>
                );

            case 'cleaning_staff':
                return (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">🧹</div>
                        <h2 className="text-2xl font-bold mb-4">Cleaning Dashboard</h2>
                        <p className="text-gray-600 mb-6">Manage your assigned cleaning tasks</p>
                        <div className="flex justify-center gap-4">
                            <Button onClick={() => router.visit('/tasks')}>
                                📋 View Tasks
                            </Button>
                            <Button variant="outline" onClick={() => router.visit('/reports')}>
                                📄 Submit Reports
                            </Button>
                        </div>
                    </div>
                );

            case 'security_staff':
                return (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">🛡️</div>
                        <h2 className="text-2xl font-bold mb-4">Security Dashboard</h2>
                        <p className="text-gray-600 mb-6">Monitor security and safety</p>
                        <div className="flex justify-center gap-4">
                            <Button onClick={() => router.visit('/security-logs')}>
                                🔒 Security Logs
                            </Button>
                            <Button variant="outline" onClick={() => router.visit('/incidents')}>
                                ⚠️ Report Incident
                            </Button>
                        </div>
                    </div>
                );

            default:
                return (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">🏠</div>
                        <h2 className="text-2xl font-bold mb-4">AccommoManager</h2>
                        <p className="text-gray-600 mb-6">Welcome to the accommodation management system</p>
                        <Button onClick={() => router.visit('/properties')}>
                            🔍 Browse Properties
                        </Button>
                    </div>
                );
        }
    };

    return (
        <AppShell>
            <Head title="Dashboard" />
            
            <div className="container mx-auto px-4 py-8">
                {/* Header */}
                <div className="mb-8">
                    <h1 className="text-3xl font-bold text-gray-900">
                        👋 Welcome, {user.name}
                    </h1>
                    <p className="text-gray-600 mt-2">
                        Role: <span className="capitalize font-semibold">{user.role.replace('_', ' ')}</span>
                    </p>
                </div>

                {/* Quick Stats */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <Card>
                        <CardHeader className="pb-2">
                            <CardDescription>System Status</CardDescription>
                            <CardTitle className="text-lg text-green-600">🟢 Online</CardTitle>
                        </CardHeader>
                    </Card>
                    
                    <Card>
                        <CardHeader className="pb-2">
                            <CardDescription>Your Activity</CardDescription>
                            <CardTitle className="text-lg">✅ Active</CardTitle>
                        </CardHeader>
                    </Card>
                    
                    <Card>
                        <CardHeader className="pb-2">
                            <CardDescription>Last Login</CardDescription>
                            <CardTitle className="text-lg">🕐 Today</CardTitle>
                        </CardHeader>
                    </Card>
                    
                    <Card>
                        <CardHeader className="pb-2">
                            <CardDescription>Messages</CardDescription>
                            <CardTitle className="text-lg">💬 0</CardTitle>
                        </CardHeader>
                    </Card>
                </div>

                {/* Role-specific content */}
                <Card>
                    <CardContent className="pt-6">
                        {getDashboardContent()}
                    </CardContent>
                </Card>

                {/* Quick Actions */}
                <Card className="mt-8">
                    <CardHeader>
                        <CardTitle>🚀 Quick Actions</CardTitle>
                        <CardDescription>Common tasks for your role</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <Button 
                                variant="outline" 
                                className="h-20 flex flex-col gap-2"
                                onClick={() => router.visit('/properties')}
                            >
                                <span className="text-2xl">🏠</span>
                                <span className="text-sm">Properties</span>
                            </Button>
                            
                            <Button 
                                variant="outline" 
                                className="h-20 flex flex-col gap-2"
                                onClick={() => router.visit('/bookings')}
                            >
                                <span className="text-2xl">📅</span>
                                <span className="text-sm">Bookings</span>
                            </Button>
                            
                            <Button 
                                variant="outline" 
                                className="h-20 flex flex-col gap-2"
                                onClick={() => router.visit('/messages')}
                            >
                                <span className="text-2xl">💬</span>
                                <span className="text-sm">Messages</span>
                            </Button>
                            
                            <Button 
                                variant="outline" 
                                className="h-20 flex flex-col gap-2"
                                onClick={() => router.visit('/settings')}
                            >
                                <span className="text-2xl">⚙️</span>
                                <span className="text-sm">Settings</span>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppShell>
    );
}