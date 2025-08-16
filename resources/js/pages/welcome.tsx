import React from 'react';
import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

export default function Welcome() {
    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            <div className="container mx-auto px-4 py-12">
                {/* Header */}
                <div className="text-center mb-16">
                    <div className="mb-6">
                        <div className="text-6xl mb-4">🏠</div>
                        <h1 className="text-5xl font-bold text-gray-900 mb-4">
                            AccommoManager
                        </h1>
                        <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                            The complete accommodation information system for property owners, guests, and service staff. 
                            Manage bookings, streamline operations, and deliver exceptional hospitality experiences.
                        </p>
                    </div>
                    
                    <div className="flex flex-wrap justify-center gap-4 mb-8">
                        <Link href="/login">
                            <Button size="lg" className="text-lg px-8 py-4">
                                🔑 Login
                            </Button>
                        </Link>
                        <Link href="/register">
                            <Button variant="outline" size="lg" className="text-lg px-8 py-4">
                                ✨ Get Started
                            </Button>
                        </Link>
                    </div>
                </div>

                {/* Feature Sections */}
                <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    {/* Property Management */}
                    <div className="bg-white rounded-lg shadow-lg p-6 border-t-4 border-blue-500">
                        <div className="text-4xl mb-4">🏢</div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-3">
                            Property Management
                        </h3>
                        <ul className="text-gray-600 space-y-2">
                            <li>• List and manage multiple properties</li>
                            <li>• Set pricing and availability</li>
                            <li>• Upload photos and amenities</li>
                            <li>• Real-time booking management</li>
                        </ul>
                    </div>

                    {/* Guest Experience */}
                    <div className="bg-white rounded-lg shadow-lg p-6 border-t-4 border-green-500">
                        <div className="text-4xl mb-4">🧳</div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-3">
                            Guest Experience
                        </h3>
                        <ul className="text-gray-600 space-y-2">
                            <li>• Search and filter properties</li>
                            <li>• Easy online booking system</li>
                            <li>• Service request management</li>
                            <li>• Rating and review system</li>
                        </ul>
                    </div>

                    {/* Service Management */}
                    <div className="bg-white rounded-lg shadow-lg p-6 border-t-4 border-purple-500">
                        <div className="text-4xl mb-4">🧹</div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-3">
                            Service Coordination
                        </h3>
                        <ul className="text-gray-600 space-y-2">
                            <li>• Cleaning task management</li>
                            <li>• Security monitoring tools</li>
                            <li>• Maintenance request tracking</li>
                            <li>• Staff communication hub</li>
                        </ul>
                    </div>

                    {/* Communication */}
                    <div className="bg-white rounded-lg shadow-lg p-6 border-t-4 border-orange-500">
                        <div className="text-4xl mb-4">💬</div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-3">
                            Real-time Communication
                        </h3>
                        <ul className="text-gray-600 space-y-2">
                            <li>• Guest-owner messaging</li>
                            <li>• Staff coordination chat</li>
                            <li>• Instant notifications</li>
                            <li>• Issue reporting system</li>
                        </ul>
                    </div>

                    {/* Analytics */}
                    <div className="bg-white rounded-lg shadow-lg p-6 border-t-4 border-red-500">
                        <div className="text-4xl mb-4">📊</div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-3">
                            Analytics Dashboard
                        </h3>
                        <ul className="text-gray-600 space-y-2">
                            <li>• Booking trends analysis</li>
                            <li>• Revenue tracking</li>
                            <li>• Occupancy rate monitoring</li>
                            <li>• Performance insights</li>
                        </ul>
                    </div>

                    {/* Security */}
                    <div className="bg-white rounded-lg shadow-lg p-6 border-t-4 border-indigo-500">
                        <div className="text-4xl mb-4">🛡️</div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-3">
                            Security & Safety
                        </h3>
                        <ul className="text-gray-600 space-y-2">
                            <li>• Access log monitoring</li>
                            <li>• Incident reporting</li>
                            <li>• Security alert system</li>
                            <li>• Emergency protocols</li>
                        </ul>
                    </div>
                </div>

                {/* User Roles Section */}
                <div className="bg-white rounded-lg shadow-lg p-8 mb-16">
                    <h2 className="text-3xl font-bold text-center text-gray-900 mb-8">
                        🎭 Multi-Role Platform
                    </h2>
                    <div className="grid md:grid-cols-2 lg:grid-cols-5 gap-6">
                        <div className="text-center">
                            <div className="text-3xl mb-3">👨‍💼</div>
                            <h4 className="font-semibold text-gray-900 mb-2">Administrator</h4>
                            <p className="text-sm text-gray-600">System oversight and management</p>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl mb-3">🏠</div>
                            <h4 className="font-semibold text-gray-900 mb-2">Property Owner</h4>
                            <p className="text-sm text-gray-600">Manage properties and bookings</p>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl mb-3">🧳</div>
                            <h4 className="font-semibold text-gray-900 mb-2">Guest</h4>
                            <p className="text-sm text-gray-600">Book and enjoy accommodations</p>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl mb-3">🧹</div>
                            <h4 className="font-semibold text-gray-900 mb-2">Cleaning Staff</h4>
                            <p className="text-sm text-gray-600">Maintain property standards</p>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl mb-3">🛡️</div>
                            <h4 className="font-semibold text-gray-900 mb-2">Security Staff</h4>
                            <p className="text-sm text-gray-600">Ensure guest safety</p>
                        </div>
                    </div>
                </div>

                {/* CTA Section */}
                <div className="text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg p-12">
                    <h2 className="text-3xl font-bold mb-4">
                        🚀 Ready to Transform Your Accommodation Business?
                    </h2>
                    <p className="text-xl mb-8 opacity-90">
                        Join thousands of property owners who trust AccommoManager for their operations
                    </p>
                    <div className="flex flex-wrap justify-center gap-4">
                        <Link href="/register">
                            <Button size="lg" variant="outline" className="bg-white text-blue-600 hover:bg-gray-100 text-lg px-8 py-4">
                                🎯 Start Free Trial
                            </Button>
                        </Link>
                        <Link href="/properties">
                            <Button size="lg" variant="outline" className="border-white text-white hover:bg-white hover:text-blue-600 text-lg px-8 py-4">
                                🔍 Browse Properties
                            </Button>
                        </Link>
                    </div>
                </div>

                {/* Footer */}
                <div className="text-center mt-16 text-gray-600">
                    <p>© 2024 AccommoManager. Streamlining accommodation management worldwide.</p>
                </div>
            </div>
        </div>
    );
}